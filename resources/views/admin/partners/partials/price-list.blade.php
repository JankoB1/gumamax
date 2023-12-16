       <div class="row">
           <div class="col-xs-12 widget-container-col ui-sortable">
               <div class="widget-box transparent ui-sortable-handle">
                   <div class="widget-header">
                       <h5 class="widget-title">Cenovnik</h5>
                       <div class="widget-toolbar">
                           <a href="#" data-action="collapse">
                               <i class="1 ace-icon fa bigger-125 fa-chevron-up"></i>
                           </a>
                       </div>
                       <div class="widget-toolbar no-border">
                       </div>
                   </div>
                   <div class="widget-body" style="display: block;">
                       <div class="widget-main">
                           {!! Former::vertical_open()->id("price-list-search")!!}
                           {!! Former::setOption('automatic_label', false) !!}
                           <fieldset>
                               <div class="row">
                                   <div class="col-sm-2">
                                       {!! Former::text('Grupa')->setAttributes(['class'=>'form-control column_filter', 'data-column'=>2])->placeholder('Grupa') !!}
                                   </div>
                                   <div class="col-sm-2">
                                       {!! Former::text('Kategorija vozila')->setAttributes(['class'=>'form-control column_filter', 'data-column'=>3])->placeholder('Kategorija vozila') !!}
                                   </div>
                                   <div class="col-sm-2">
                                       {!! Former::text('Description')->setAttributes(['class'=>'form-control column_filter', 'data-column'=>4])->placeholder('naziv usluge') !!}
                                   </div>
                                   <div class="col-sm-2">
                                       {!! Former::text('Opis')->setAttributes(['class'=>'form-control column_filter', 'data-column'=>5])->placeholder('Opis usluge') !!}
                                   </div>
                                   <div class="col-sm-1">
                                       {!! Former::text('Prečnik')->setAttributes(['class'=>'form-control column_filter', 'data-column'=>6])->placeholder('Prečnik') !!}
                                   </div>
                                   <div class="col-sm-1">
                                       {!! Former::text('Materijal')->setAttributes(['class'=>'form-control column_filter', 'data-column'=>7])->placeholder('Materijal') !!}
                                   </div>
                                   <div class="col-sm-2">
                                       {!! Former::text('Cena')->setAttributes(['class'=>'form-control column_filter', 'data-column'=>8])->placeholder('Cena') !!}
                                   </div>
                               </div>
                           </fieldset>
                           {!! Former::setOption('automatic_label', true) !!}
                           {!! Former::close() !!}
                       </div>

                       <div class="widget-toolbox padding-8 clearfix">
                           <button class="btn btn-xs btn-default pull-right clear_filter">
                               <i class="ace-icon fa fa-times"></i>
                               <span class="bigger-110">Poništi filter</span>
                           </button>
                       </div>
                   </div>
               </div>
           </div>
    <div class="col-xs-12">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="partner-price-list-table" role="grid">
            <thead>
            <tr>
                <th>ProductId</th>
                <th>Id</th>
                <th>Grupa</th>
                <th>Kateg. vozila</th>
                <th>Naziv</th>
                <th>Opis</th>
                <th>Prečnik</th>
                <th>Materijal</th>
                <th>Cena (sa PDV)</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
