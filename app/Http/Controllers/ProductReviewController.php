<?php namespace App\Http\Controllers;

use Exception;
use Delmax\Products\SaveProductReviewRequest;
use App\Http\Requests;
use App\Gumamax\Products\Repositories\ProductRepositoryInterface;
use Delmax\Products\ProductReview;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;

class ProductReviewController extends DmxBaseController {

    protected $productRepo;

    /**
     * @param ProductRepositoryInterface $repository
     */
    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct();

        $this->productRepo = $repository;

    }

    public function index($status){

        if (in_array($status, ['opened', 'approved', 'rejected'])) {

            return view('admin.product-review.index', compact('status'));

        } else {

            //Nepoznat status
            abort(404);
        }

    }

    public function edit(Request $request, $id){

        $model = ProductReview::find($id);

        if ($model){

            $formMethod = 'PUT';

            $formUrl = route('admin.product-review.update', [$id]);

            return view('admin.product-review.edit', compact('model', 'formMethod', 'formUrl'));

        }

        abort(404);

    }

    public function update(Request $request, $id){

        $model = ProductReview::find($id);

        if ($model){

            $data = $request->only('approved_at', 'rejected_at');

            if (!empty($data['approved_at'])) {

                $data['approved_by_user_id'] = auth()->user()->user_id;

            }

            if (!empty($data['rejected_at'])) {

                $data['rejected_by_user_id'] = auth()->user()->user_id;

            }

            $model->update($data);

            $model->save();

            return redirect()->route('admin.product-review.status', 'opened');

        }

        abort(404);

    }

	public function getAllProductsReviews($id)
	{

        $query = [$id];

        $data = $this->productRepo->getById($query);

        if ($data) {

            $product = $data[0];

            return view('product.reviews-all', [
				'product' => $product,
				'reviews' => ProductReview::getAllReviews($id, 'product')
            ]);
        }

        abort(404);

	}

	public function reviewProduct($id)
	{

        $data = $this->productRepo->findById($id);

        if ($data) {

            $product = $data;

            return view('product.review', compact('product'));
        }

        abort(404);

	}

	public function storeProductReview(SaveProductReviewRequest $request){

        $review = ProductReview::make($request->all());

        try {
            $review->save();

            return 'true';
        } catch (Exception $e) {
            return 'false';
        }
	}

    public function apiDatatables($status){

        switch ($status){
            case 'opened': {
                $query = ProductReview::whereNull('rejected_at')->whereNull('approved_at')->get();
                break;
            }
            case 'approved':{
                $query = ProductReview::whereNotNull('approved_at')->get();
                break;
            }
            case 'rejected':{
                $query = ProductReview::whereNotNull('rejected_at')->get();
                break;
            }
            default :{
                //Nepoznat status
                abort(404);
                break;
            }

        }

        if ($query) {

            $d = datatables()::of($query);

            return $d->make(true);
        }
    }


}
