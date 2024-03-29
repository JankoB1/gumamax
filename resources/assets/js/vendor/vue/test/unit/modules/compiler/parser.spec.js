import { parse } from 'compiler/parser/index'
import { extend } from 'shared/util'
import { baseOptions } from 'web/compiler/index'
import { isIE } from 'core/util/.env'

describe('parser', () => {
  it('simple element', () => {
    const ast = parse('<h1>hello world</h1>', baseOptions)
    expect(ast.tag).toBe('h1')
    expect(ast.plain).toBe(true)
    expect(ast.children[0].text).toBe('hello world')
  })

  it('interpolation in element', () => {
    const ast = parse('<h1>{{msg}}</h1>', baseOptions)
    expect(ast.tag).toBe('h1')
    expect(ast.plain).toBe(true)
    expect(ast.children[0].expression).toBe('_s(msg)')
  })

  it('child elements', () => {
    const ast = parse('<ul><li>hello world</li></ul>', baseOptions)
    expect(ast.tag).toBe('ul')
    expect(ast.plain).toBe(true)
    expect(ast.children[0].tag).toBe('li')
    expect(ast.children[0].plain).toBe(true)
    expect(ast.children[0].children[0].text).toBe('hello world')
    expect(ast.children[0].parent).toBe(ast)
  })

  it('unary element', () => {
    const ast = parse('<hr>', baseOptions)
    expect(ast.tag).toBe('hr')
    expect(ast.plain).toBe(true)
    expect(ast.children.length).toBe(0)
  })

  it('svg element', () => {
    const ast = parse('<svg><text>hello world</text></svg>', baseOptions)
    expect(ast.tag).toBe('svg')
    expect(ast.ns).toBe('svg')
    expect(ast.plain).toBe(true)
    expect(ast.children[0].tag).toBe('text')
    expect(ast.children[0].children[0].text).toBe('hello world')
    expect(ast.children[0].parent).toBe(ast)
  })

  it('camelCase element', () => {
    const ast = parse('<MyComponent><p>hello world</p></MyComponent>', baseOptions)
    expect(ast.tag).toBe('MyComponent')
    expect(ast.plain).toBe(true)
    expect(ast.children[0].tag).toBe('p')
    expect(ast.children[0].plain).toBe(true)
    expect(ast.children[0].children[0].text).toBe('hello world')
    expect(ast.children[0].parent).toBe(ast)
  })

  it('forbidden element', () => {
    // style
    const styleAst = parse('<style>error { color: red; }</style>', baseOptions)
    expect(styleAst.tag).toBe('style')
    expect(styleAst.plain).toBe(true)
    expect(styleAst.forbidden).toBe(true)
    expect(styleAst.children[0].text).toBe('error { color: red; }')
    expect('Templates should only be responsible for mapping the state').toHaveBeenWarned()
    // script
    const scriptAst = parse('<script type="text/javascript">alert("hello world!")</script>', baseOptions)
    expect(scriptAst.tag).toBe('script')
    expect(scriptAst.plain).toBe(false)
    expect(scriptAst.forbidden).toBe(true)
    expect(scriptAst.children[0].text).toBe('alert("hello world!")')
    expect('Templates should only be responsible for mapping the state').toHaveBeenWarned()
  })

  it('not contain root element', () => {
    parse('hello world', baseOptions)
    expect('Component template requires a root element, rather than just text').toHaveBeenWarned()
  })

  it('warn multiple root elements', () => {
    parse('<div></div><div></div>', baseOptions)
    expect('Component template should contain exactly one root element:\n\n<div></div><div></div>').toHaveBeenWarned()
  })

  it('not warn 2 root elements with v-if and v-else', () => {
    parse('<div v-if="1"></div><div v-else></div>', baseOptions)
    expect('Component template should contain exactly one root element')
      .not.toHaveBeenWarned()
  })

  it('not warn 2 root elements with v-if and v-else on separate lines', () => {
    parse(`
      <div v-if="1"></div>
      <div v-else></div>
    `, baseOptions)
    expect('Component template should contain exactly one root element')
      .not.toHaveBeenWarned()
  })

  it('generate correct ast for 2 root elements with v-if and v-else on separate lines', () => {
    const ast = parse(`
      <div v-if="1"></div>
      <p v-else></p>
    `, baseOptions)
    expect(ast.tag).toBe('div')
    expect(ast.elseBlock.tag).toBe('p')
  })

  it('warn 2 root elements with v-if', () => {
    parse('<div v-if="1"></div><div v-if="2"></div>', baseOptions)
    expect('Component template should contain exactly one root element:\n\n<div v-if="1"></div><div v-if="2"></div>')
      .toHaveBeenWarned()
  })

  it('warn 3 root elements with v-if and v-else on first 2', () => {
    parse('<div v-if="1"></div><div v-else></div><div></div>', baseOptions)
    expect('Component template should contain exactly one root element:\n\n<div v-if="1"></div><div v-else></div><div></div>')
      .toHaveBeenWarned()
  })

  it('warn 2 root elements with v-if and v-else with v-for on 2nd', () => {
    parse('<div v-if="1"></div><div v-else v-for="i in [1]"></div>', baseOptions)
    expect('Cannot use v-for on stateful component root element because it renders multiple elements:\n<div v-if="1"></div><div v-else v-for="i in [1]"></div>')
      .toHaveBeenWarned()
  })

  it('warn <template> as root element', () => {
    parse('<template></template>', baseOptions)
    expect('Cannot use <template> as component root element').toHaveBeenWarned()
  })

  it('warn <slot> as root element', () => {
    parse('<slot></slot>', baseOptions)
    expect('Cannot use <slot> as component root element').toHaveBeenWarned()
  })

  it('warn v-for on root element', () => {
    parse('<div v-for="item in items"></div>', baseOptions)
    expect('Cannot use v-for on stateful component root element').toHaveBeenWarned()
  })

  it('warn <template> key', () => {
    parse('<div><template v-for="i in 10" :key="i"></template></div>', baseOptions)
    expect('<template> cannot be keyed').toHaveBeenWarned()
  })

  it('v-pre directive', () => {
    const ast = parse('<div v-pre id="message1"><p>{{msg}}</p></div>', baseOptions)
    expect(ast.pre).toBe(true)
    expect(ast.attrs[0].name).toBe('id')
    expect(ast.attrs[0].value).toBe('"message1"')
    expect(ast.children[0].children[0].text).toBe('{{msg}}')
  })

  it('v-for directive basic syntax', () => {
    const ast = parse('<ul><li v-for="item in items"></li><ul>', baseOptions)
    const liAst = ast.children[0]
    expect(liAst.for).toBe('items')
    expect(liAst.alias).toBe('item')
  })

  it('v-for directive iteration syntax', () => {
    const ast = parse('<ul><li v-for="(item, index) in items"></li><ul>', baseOptions)
    const liAst = ast.children[0]
    expect(liAst.for).toBe('items')
    expect(liAst.alias).toBe('item')
    expect(liAst.iterator1).toBe('index')
    expect(liAst.iterator2).toBeUndefined()
  })

  it('v-for directive iteration syntax (multiple)', () => {
    const ast = parse('<ul><li v-for="(item, key, index) in items"></li><ul>', baseOptions)
    const liAst = ast.children[0]
    expect(liAst.for).toBe('items')
    expect(liAst.alias).toBe('item')
    expect(liAst.iterator1).toBe('key')
    expect(liAst.iterator2).toBe('index')
  })

  it('v-for directive key', () => {
    const ast = parse('<ul><li v-for="item in items" :key="item.uid"></li><ul>', baseOptions)
    const liAst = ast.children[0]
    expect(liAst.for).toBe('items')
    expect(liAst.alias).toBe('item')
    expect(liAst.key).toBe('item.uid')
  })

  it('v-for directive invalid syntax', () => {
    parse('<ul><li v-for="item into items"></li><ul>', baseOptions)
    expect('Invalid v-for expression').toHaveBeenWarned()
  })

  it('v-if directive syntax', () => {
    const ast = parse('<p v-if="show">hello world</p>', baseOptions)
    expect(ast.if).toBe('show')
  })

  it('v-else directive syntax', () => {
    const ast = parse('<div><p v-if="show">hello</p><p v-else>world</p></div>', baseOptions)
    const ifAst = ast.children[0]
    const elseAst = ifAst.elseBlock
    expect(elseAst.else).toBe(true)
    expect(elseAst.children[0].text).toBe('world')
    expect(elseAst.parent).toBe(ast)
  })

  it('v-else directive invalid syntax', () => {
    parse('<div><p v-else>world</p></div>', baseOptions)
    expect('v-else used on element').toHaveBeenWarned()
  })

  it('v-once directive syntax', () => {
    const ast = parse('<p v-once>world</p>', baseOptions)
    expect(ast.once).toBe(true)
  })

  it('slot tag single syntax', () => {
    const ast = parse('<slot></slot>', baseOptions)
    expect(ast.tag).toBe('slot')
    expect(ast.slotName).toBeUndefined()
  })

  it('slot tag namped syntax', () => {
    const ast = parse('<slot name="one">hello world</slot>', baseOptions)
    expect(ast.tag).toBe('slot')
    expect(ast.slotName).toBe('"one"')
  })

  it('slot target', () => {
    const ast = parse('<p slot="one">hello world</p>', baseOptions)
    expect(ast.slotTarget).toBe('"one"')
  })

  it('component properties', () => {
    const ast = parse('<my-component :msg="hello"></my-component>', baseOptions)
    expect(ast.attrs[0].name).toBe('msg')
    expect(ast.attrs[0].value).toBe('hello')
  })

  it('component "is" attribute', () => {
    const ast = parse('<my-component is="component1"></my-component>', baseOptions)
    expect(ast.component).toBe('"component1"')
  })

  it('component "inline-template" attribute', () => {
    const ast = parse('<my-component inline-template>hello world</my-component>', baseOptions)
    expect(ast.inlineTemplate).toBe(true)
  })

  it('class binding', () => {
    // static
    const ast1 = parse('<p class="class1">hello world</p>', baseOptions)
    expect(ast1.staticClass).toBe('"class1"')
    // dynamic
    const ast2 = parse('<p :class="class1">hello world</p>', baseOptions)
    expect(ast2.classBinding).toBe('class1')
    // interpolation warning
    parse('<p class="{{error}}">hello world</p>', baseOptions)
    expect('Interpolation inside attributes has been removed').toHaveBeenWarned()
  })

  it('style binding', () => {
    const ast = parse('<p :style="error">hello world</p>', baseOptions)
    expect(ast.styleBinding).toBe('error')
  })

  it('attribute with v-bind', () => {
    const ast = parse('<input type="text" name="field1" :value="msg">', baseOptions)
    expect(ast.attrsList[0].name).toBe('type')
    expect(ast.attrsList[0].value).toBe('text')
    expect(ast.attrsList[1].name).toBe('name')
    expect(ast.attrsList[1].value).toBe('field1')
    expect(ast.attrsMap['type']).toBe('text')
    expect(ast.attrsMap['name']).toBe('field1')
    expect(ast.attrs[0].name).toBe('type')
    expect(ast.attrs[0].value).toBe('"text"')
    expect(ast.attrs[1].name).toBe('name')
    expect(ast.attrs[1].value).toBe('"field1"')
    expect(ast.props[0].name).toBe('value')
    expect(ast.props[0].value).toBe('msg')
  })

  it('attribute with v-on', () => {
    const ast = parse('<input type="text" name="field1" :value="msg" @input="onInput">', baseOptions)
    expect(ast.events.input.value).toBe('onInput')
  })

  it('attribute with directive', () => {
    const ast = parse('<input type="text" name="field1" :value="msg" v-validate:field1="required">', baseOptions)
    expect(ast.directives[0].name).toBe('validate')
    expect(ast.directives[0].value).toBe('required')
    expect(ast.directives[0].arg).toBe('field1')
  })

  it('attribute with modifiered directive', () => {
    const ast = parse('<input type="text" name="field1" :value="msg" v-validate.on.off>', baseOptions)
    expect(ast.directives[0].modifiers.on).toBe(true)
    expect(ast.directives[0].modifiers.off).toBe(true)
  })

  it('literal attribute', () => {
    // basic
    const ast1 = parse('<input type="text" name="field1" value="hello world">', baseOptions)
    expect(ast1.attrsList[0].name).toBe('type')
    expect(ast1.attrsList[0].value).toBe('text')
    expect(ast1.attrsList[1].name).toBe('name')
    expect(ast1.attrsList[1].value).toBe('field1')
    expect(ast1.attrsList[2].name).toBe('value')
    expect(ast1.attrsList[2].value).toBe('hello world')
    expect(ast1.attrsMap['type']).toBe('text')
    expect(ast1.attrsMap['name']).toBe('field1')
    expect(ast1.attrsMap['value']).toBe('hello world')
    expect(ast1.attrs[0].name).toBe('type')
    expect(ast1.attrs[0].value).toBe('"text"')
    expect(ast1.attrs[1].name).toBe('name')
    expect(ast1.attrs[1].value).toBe('"field1"')
    expect(ast1.attrs[2].name).toBe('value')
    expect(ast1.attrs[2].value).toBe('"hello world"')
    // interpolation warning
    parse('<input type="text" name="field1" value="{{msg}}">', baseOptions)
    expect('Interpolation inside attributes has been removed').toHaveBeenWarned()
  })

  if (!isIE) {
    it('duplicate attribute', () => {
      parse('<p class="class1" class="class1">hello world</p>', baseOptions)
      expect('duplicate attribute').toHaveBeenWarned()
    })
  }

  it('custom delimiter', () => {
    const ast = parse('<p>{msg}</p>', extend({ delimiters: ['{', '}'] }, baseOptions))
    expect(ast.children[0].expression).toBe('_s(msg)')
  })

  it('not specified getTagNamespace option', () => {
    const options = extend({}, baseOptions)
    delete options.getTagNamespace
    const ast = parse('<svg><text>hello world</text></svg>', options)
    expect(ast.tag).toBe('svg')
    expect(ast.ns).toBeUndefined()
  })

  it('not specified mustUseProp', () => {
    const options = extend({}, baseOptions)
    delete options.mustUseProp
    const ast = parse('<input type="text" name="field1" :value="msg">', options)
    expect(ast.props).toBeUndefined()
  })

  it('pre/post transforms', () => {
    const options = extend({}, baseOptions)
    const spy1 = jasmine.createSpy('preTransform')
    const spy2 = jasmine.createSpy('postTransform')
    options.modules = options.modules.concat([{
      preTransformNode (el) {
        spy1(el.tag)
      },
      postTransformNode (el) {
        expect(el.attrs.length).toBe(1)
        spy2(el.tag)
      }
    }])
    parse('<img v-pre src="hi">', options)
    expect(spy1).toHaveBeenCalledWith('img')
    expect(spy2).toHaveBeenCalledWith('img')
  })

  it('preserve whitespace in <pre> tag', function () {
    const options = extend({}, baseOptions)
    const ast = parse('<pre><code>  \n<span>hi</span>\n  </code></pre>', options)
    const code = ast.children[0]
    expect(code.children[0].type).toBe(3)
    expect(code.children[0].text).toBe('  \n')
    expect(code.children[2].type).toBe(3)
    expect(code.children[2].text).toBe('\n  ')
  })

  it('forgivingly handle < in plain text', () => {
    const options = extend({}, baseOptions)
    const ast = parse('<p>1 < 2 < 3</p>', options)
    expect(ast.tag).toBe('p')
    expect(ast.children.length).toBe(1)
    expect(ast.children[0].type).toBe(3)
    expect(ast.children[0].text).toBe('1 < 2 < 3')
  })

  it('IE conditional comments', () => {
    const options = extend({}, baseOptions)
    const ast = parse(`
      <div>
        <!--[if lte IE 8]>
          <p>Test 1</p>
        <![endif]-->
      </div>
    `, options)
    expect(ast.tag).toBe('div')
    expect(ast.chilldren).toBeUndefined()
  })
})
