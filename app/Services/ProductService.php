<?php

namespace App\Services;

use App\Helpers\TextSystemConst;
use App\Http\Requests\Admin\StoreProductColorRequest;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\StoreSizeProductRequest;
use App\Http\Requests\Admin\UpdateProductColorRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Requests\Admin\UpdateSizeProductRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Size;
use App\Repository\Eloquent\BrandRepository;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\ColorRepository;
use App\Repository\Eloquent\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductService 
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var BrandRepository
     */
    private $brandRepository;

    /**
     * @var ColorRepository
     */
    private $colorRepository;

    /**
     * ProductService constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductRepository $productRepository, 
        CategoryRepository $categoryRepository,
        BrandRepository $brandRepository,
        ColorRepository $colorRepository,
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->brandRepository = $brandRepository;
        $this->colorRepository = $colorRepository;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // lấy tất cả sản phẩm trong database
        $list = $this->productRepository->all();

        $tableCrud = [
            'headers' => [
                [
                    'text' => 'Mã SP',
                    'key' => 'id',
                ],
                [
                    'text' => 'Tên SP',
                    'key' => 'name',
                ],
                [
                    'text' => 'Hình Ảnh',
                    'key' => 'img',
                    'img' => [
                        'url' => 'asset/client/images/products/small/',
                        'style' => 'width: 100px;'
                    ],
                ],
                [
                    'text' => 'Danh Mục',
                    'key' => 'category_name',
                ],
                [
                    'text' => 'Số lượng',
                    'key' => 'quantity',
                ],
                [
                    'text' => 'Giá',
                    'key' => 'price_sell',
                    'format' => true,
                ],
                // [
                //     'text' => 'Trạng Thái',
                //     'key' => 'status',
                //     'status' => [
                //         [
                //             'text' => 'Hoạt động',
                //             'value' => 1,
                //             'class' => 'badge bg-success'
                //         ],
                //         [
                //             'text' => 'Tạm dừng',
                //             'value' => 0,
                //             'class' => 'badge bg-danger'
                //         ],
                //     ],
                // ],
            ],
            'actions' => [
                'text'          => "Thao Tác",
                'create'        => true,
                'createExcel'   => false,
                'edit'          => true,
                'deleteAll'     => false,
                'delete'        => true,
                'viewDetail'    => false,
            ],
            'routes' => [
                'create' => 'admin.products_create',
                'delete' => 'admin.products_delete',
                'edit' => 'admin.products_edit',
            ],
            'list' => $list,
        ];

        return [
            'title' => TextLayoutTitle("product"),
            'tableCrud' => $tableCrud,
        ];
    }

    /**
     * Show the form for creating a new user.
     *
     * @return array
     */
    public function create()
    {
        try {
            $categoriesParent = category_header();
            $brands = $this->brandRepository->all();
            //Rules form
            $rules = [
                'name' => [
                    'required' => true,
                ],
                'price_import' => [
                    'required' => true,
                    'number' => true
                ],
                'price_sell' => [
                    'required' => true,
                    'greaterThanImportPrice' => true,
                    'number' => true
                ],
                'brand_id' => [
                    'required' => true,
                ],
                'category_id' => [
                    'required' => true,
                ],
                'description' => [
                    'required' => true,
                ],
                'file-input' => [
                    'required' => true,
                ],
            ];

            // Messages eror rules
            $messages = [
                'name' => [
                    'required' => __('message.required', ['attribute' => 'tên sản phẩm']),
                ],
                'price_import' => [
                    'required' => __('message.required', ['attribute' => 'giá nhập sản phẩm']),
                    'number' => 'Giá nhập phải là số'
                ],
                'price_sell' => [
                    'required' => __('message.required', ['attribute' => 'giá bán sản phẩm']),
                    'greaterThanImportPrice' => 'Giá bán phải lớn hơn giá nhập',
                    'number' => 'Giá bán phải là số'
                ],
                'brand_id' => [
                    'required' => __('message.required', ['attribute' => 'thương hiệu sản phẩm']),
                ],
                'category_id' => [
                    'required' => __('message.required', ['attribute' => 'danh mục']),
                ],
                'description' => [
                    'required' => __('message.required', ['attribute' => 'mô tả']),
                ],
                'file-input' => [
                    'required' => __('message.required', ['attribute' => 'hình ảnh']),
                ],
            ];
            return [
                'title' => TextLayoutTitle("create_product"),
                'categoriesParent' => $categoriesParent,
                'messages' => $messages,
                'rules' => $rules,
                'brands' => $brands,
            ];
        } catch (Exception) {
            return [];
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();
            $imageName = time().'.'.request()->img->getClientOriginalExtension();
            request()->img->move(public_path('asset/client/images/products/small'), $imageName);
            $data['img'] = $imageName;
            $product = $this->productRepository->create($data);
            return redirect()->route('admin.products_color', $product->id)->with('success', TextSystemConst::CREATE_SUCCESS);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route('admin.product_index')->with('error', TextSystemConst::CREATE_FAILED);
        }
    }

    public function edit(Product $product)
    {
        $categoriesParent = category_header();
        $brands = $this->brandRepository->all();
        //Rules form  
        $rules = [
            'name' => [
                'required' => true,
            ],
            'price_import' => [
                'required' => true,
                'number' => true,
            ],
            'price_sell' => [
                'required' => true,
                'greaterThanImportPrice' => true,
                'number' => true
            ],
            'brand_id' => [
                'required' => true,
            ],
            'category_id' => [
                'required' => true,
            ],
            'description' => [
                'required' => true,
            ],
        ];

        // Messages eror rules
        $messages = [
            'name' => [
                'required' => __('message.required', ['attribute' => 'tên sản phẩm']),
            ],
            'price_import' => [
                'required' => __('message.required', ['attribute' => 'giá nhập sản phẩm']),
                'number' => 'Giá nhập phải là số'
            ],
            'price_sell' => [
                'required' => __('message.required', ['attribute' => 'giá bán sản phẩm']),
                'greaterThanImportPrice' => 'Giá bán phải lớn hơn giá nhập',
                'number' => 'Giá bán phải là số'
            ],
            'brand_id' => [
                'required' => __('message.required', ['attribute' => 'thương hiệu sản phẩm']),
            ],
            'category_id' => [
                'required' => __('message.required', ['attribute' => 'danh mục']),
            ],
            'description' => [
                'required' => __('message.required', ['attribute' => 'mô tả']),
            ],
        ];
        return [
            'title' => TextLayoutTitle("edit_product"),
            'categoriesParent' => $categoriesParent,
            'messages' => $messages,
            'rules' => $rules,
            'brands' => $brands,
            'product' => $product,
            'routeSize' => route('admin.products_size', $product->id),
            'routeColor' => route('admin.products_color', $product->id),
        ];
    }

    public function update(UpdateProductRequest $request ,Product $product)
    {
        try {
            $data = $request->validated();
            // nếu như có chỉnh sửa hình ảnh thì đẩy cái hình đó vào thư mục asset/client/images/products/small
            if ($request->img) {
                $imageName = time().'.'.request()->img->getClientOriginalExtension();
                request()->img->move(public_path('asset/client/images/products/small'), $imageName);
                $data['img'] = $imageName;
            }
            $product->update($data);
            return redirect()->route('admin.products_edit', $product->id)->with('success', TextSystemConst::UPDATE_SUCCESS);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->route('admin.products_index')->with('error', TextSystemConst::UPDATE_FAILED);
        }
    }

    public function delete(Request $request)
    {
        try{
            // Soft delete - chỉ đánh dấu deleted_at
            if($this->productRepository->delete($this->productRepository->find($request->id))) {
                return response()->json(['status' => 'success', 'message' => 'Đã chuyển sản phẩm vào thùng rác'], 200);
            }

            return response()->json(['status' => 'failed', 'message' => TextSystemConst::DELETE_FAILED], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => TextSystemConst::SYSTEM_ERROR], 200);
        }
    }

    public function trash()
    {
        // Lấy tất cả sản phẩm đã xóa (soft deleted)
        $list = Product::onlyTrashed()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->orderByDesc('products.deleted_at')
            ->get();

        $tableCrud = [
            'headers' => [
                [
                    'text' => 'Mã SP',
                    'key' => 'id',
                ],
                [
                    'text' => 'Tên SP',
                    'key' => 'name',
                ],
                [
                    'text' => 'Hình Ảnh',
                    'key' => 'img',
                    'img' => [
                        'url' => 'asset/client/images/products/small/',
                        'style' => 'width: 100px;'
                    ],
                ],
                [
                    'text' => 'Danh Mục',
                    'key' => 'category_name',
                ],
                [
                    'text' => 'Giá',
                    'key' => 'price_sell',
                    'format' => true,
                ],
                [
                    'text' => 'Ngày xóa',
                    'key' => 'deleted_at',
                ],
            ],
            'actions' => [
                'text'          => "Thao Tác",
                'create'        => false,
                'createExcel'   => false,
                'edit'          => false,
                'deleteAll'     => false,
                'delete'        => true,
                'restore'       => true,
                'viewDetail'    => false,
            ],
            'routes' => [
                'delete' => 'admin.products_force_delete',
                'restore' => 'admin.products_restore',
            ],
            'list' => $list,
        ];

        return [
            'title' => 'Thùng Rác - Sản Phẩm Đã Xóa',
            'tableCrud' => $tableCrud,
        ];
    }

    public function forceDelete(Request $request)
    {
        try{
            $product = Product::withTrashed()->find($request->id);
            if($product) {
                // Lấy danh sách product_color_ids trước khi xóa
                $productColorIds = DB::table('products_color')
                    ->where('product_id', $product->id)
                    ->pluck('id');
                
                // Xóa các kích thước liên quan (products_size)
                if ($productColorIds->isNotEmpty()) {
                    DB::table('products_size')->whereIn('product_color_id', $productColorIds)->delete();
                }
                
                // Xóa các màu sắc liên quan (products_color)
                DB::table('products_color')->where('product_id', $product->id)->delete();
                
                // Xóa vĩnh viễn sản phẩm khỏi database
                $product->forceDelete();
                return response()->json(['status' => 'success', 'message' => 'Đã xóa vĩnh viễn sản phẩm khỏi database'], 200);
            }

            return response()->json(['status' => 'failed', 'message' => TextSystemConst::DELETE_FAILED], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function restore(Request $request)
    {
        try{
            $product = Product::withTrashed()->find($request->id);
            if($product && $product->trashed()) {
                // Khôi phục sản phẩm
                $product->restore();
                return response()->json(['status' => 'success', 'message' => 'Đã khôi phục sản phẩm thành công'], 200);
            }

            return response()->json(['status' => 'failed', 'message' => 'Không thể khôi phục sản phẩm'], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'error', 'message' => TextSystemConst::SYSTEM_ERROR], 200);
        }
    }

    public function getCategoryByParent(Request $request)
    {
        try {
            return $this->categoryRepository->where(['parent_id' => $request->parent_id]);
        } catch (Exception) {
            return null;
        }
    }

    // hiển thị màu sản phẩm và thêm mới màu
    public function createColor(Product $product)
    {
        // lấy những màu sắc có thể thêm mới
        $colors = Color::whereNotIn('id', function($query) use($product) {
            $query->select('color_id')
                  ->from('products_color')
                  ->where('product_id', '=', $product->id)
                  ->whereNull('deleted_at');
        })
        ->get();
        // lấy ra danh sách màu hiện có của sản phẩm đó
        $productColors = ProductColor::where('product_id', $product->id)->get();

        return [
            'title' => 'Màu Sản Phẩm',
            'colors' => $colors,
            'product' => $product,
            'productColors' => $productColors,
            'routeSize' => route('admin.products_size', $product->id),
            'routeProduct' => route('admin.products_edit', $product->id),
        ];
    }

    // thêm màu thuộc 1 sản phẩm vào database
    public function storeColor(StoreProductColorRequest $request, Product $product)
    {
        try {
            // đẩy hình vào thư mục asset/client/images/products/small
            $imageName = time().'.'.request()->img->getClientOriginalExtension();
            $colorId = $request->color_id;
            request()->img->move(public_path('asset/client/images/products/small'), $imageName);

            // kiểm tra xem màu đó đã tồn tại hay chưa, nếu tồn tại rồi thì báo lỗi
            $checkColorExist = $this->productRepository->checkProductColorExist($product->id, $colorId);
            if ($checkColorExist > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Màu này đã tồn tại'
                ], 200);
            }
            //thêm màu vào databse
            ProductColor::create([
                'img' => $imageName,
                'color_id' => $colorId,
                'product_id' => $product->id
            ]);
            Session::flash('success', 'Thêm màu thành công');
            // trả về kết quả
            return response()->json([
                'status' => true,
                'route' => route('admin.products_color', $product->id),
            ], 200);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra vui lòng thử lại'
            ], 200);
        }
    }

    //lấy thông tin màu sản phẩm để chỉnh sửa
    public function editColor(ProductColor $productColor)
    {
        // lấy ra các màu có thể chỉnh sửa
        $colors = Color::whereNotIn('id', function($query) use($productColor) {
            $query->select('color_id')
                  ->from('products_color')
                  ->where('product_id', '=', $productColor->product_id)
                  ->where('color_id', '!=', $productColor->color_id)
                  ->whereNull('deleted_at');
        })
        ->get();
        // trả về kết quả
        return response()->json([
            'productColor' => $productColor,
            'colors' => $colors
        ], 200);
    }

    // cập nhật thông màu vào database
    public function updateColor(UpdateProductColorRequest $request, ProductColor $productColor)
    {
        try {
            $data = $request->validated();
            // nếu có chỉnh sửa màu thì đây hình vào thư mục asset/client/images/products/small
            if ($request->img) {
                $imageName = time().'.'.request()->img->getClientOriginalExtension();
                request()->img->move(public_path('asset/client/images/products/small'), $imageName);
                $data['img'] = $imageName;
            }
            // cập nhật dữ liệu vào databse
            $productColor->update($data);
            Session::flash('success', 'Sửa màu thành công');
            // trả về kết quả
            return response()->json([
                'status' => true,
                'route' => route('admin.products_color', $productColor->product_id),
            ], 200);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra vui lòng thử lại'
            ], 200);
        }
    }

    // xóa màu sản phẩm
    public function deleteColor(ProductColor $productColor)
    {
        // nếu xóa thành công thì trả về kêt quả thành công và ngược lại
        if ($productColor->delete()) {
            $data = [
                'status' => true,
                'message' => 'Xóa màu thành công'
            ];
        } else {
            $data = [
                'status' => false,
                'message' => 'Xóa thất bại vui lòng kiểm tra lại'
            ];
        }
        return response()->json($data, 200);
    }

    // hiển thị view để thêm mới kích thước và hiển thị tất cả các kích thước có trong sản phẩm
    public function createSize(Product $product)
    {
        // lấy ra những kích thước có thể thêm
        $productSizes = ProductSize::select('products_size.id as id', 'sizes.name as size_name', 'colors.name as color_name', 'products_size.quantity as quanity')
        ->join('products_color', 'products_color.id', '=', 'products_size.product_color_id')
        ->join('sizes', 'sizes.id', '=', 'products_size.size_id')
        ->join('colors', 'colors.id', '=', 'products_color.color_id')
        ->where('products_color.product_id', '=', $product->id)
        ->whereNull('products_color.deleted_at')
        ->orderByDesc('id')
        ->get();

        // lấy tất cả kích thước có trong sản phẩm để hiển thị
        $productColors = ProductColor::where('product_id', $product->id)->get();
        
        return [
            'title' => 'Kích thước sản phẩm',
            'routeColor' => route('admin.products_color', $product->id),
            'routeProduct' => route('admin.products_edit', $product->id),
            'productSizes' => $productSizes,
            'productColors' => $productColors,
            'product' => $product,
        ];
    }

    public function getSizeByProductColor(Request $request)
    {
        $sizes = Size::whereNotIn('sizes.id', function ($query) use ($request) {
            $query->select('size_id')
                  ->from('products_size')
                  ->where('product_color_id', '=', $request->product_color_id)
                  ->whereNull('deleted_at')
                  ;
        })->get();

        return response()->json($sizes, 200);
    }

    // lấy thông tin kích thước để chỉnh sửa
    public function getSizeByProductColorEdit(ProductSize $productSize)
    {
        $data = [
            'quantity' => $productSize->quantity,
            'size' => $productSize->size->name,
        ];
        return response()->json($data, 200);
    }

    // thực hiện thêm mới kích thước vào database
    public function storeSizeProduct(StoreSizeProductRequest $request, Product $product)
    {
        DB::table('products_size')->insert([
            'size_id' => $request->size_id,
            'product_color_id' => $request->product_color_id,
            'quantity' => $request->quantity,
        ]);
        Session::flash('success', 'Thêm kích thước thành công');
        
        return response()->json([
            'status' => true,
            'route' => route('admin.products_size', $product->id),
        ], 200);
    }

    // xóa kích thước của sản phẩm
    public function deleteSizeProduct(ProductSize $productSize)
    {
        // nếu xóa thành công thì thông thành công và ngược lại
        if ($productSize->delete()) {
            $data = [
                'status' => true,
                'message' => 'Xóa kích thước thành công'
            ];
        } else {
            $data = [
                'status' => false,
                'message' => 'Xóa thất bại vui lòng kiểm tra lại'
            ];
        }
        return response()->json($data, 200);
    }

    //lấy ra kích thước có thể chỉnh sửa
    public function editSizeProduct(ProductSize $productSize, Product $product)
    {
        $colors = DB::table('products_color')
        ->join('colors', 'colors.id', '=', 'products_color.color_id')
        ->select('colors.name as color_name', 'products_color.*')
        ->where('products_color.product_id', '=', $product->id)
        ->whereNull('products_color.deleted_at')
        ->whereNull('colors.deleted_at')
        ->get();
        $data = [
            'colors' => $colors,
            'productColor' => $productSize->productColor->color_id,
        ];

        return response()->json($data);
    }

    // thực hiện cập nhật thông tin thước khi chỉnh sửa vào database
    public function updateSizeProduct(ProductSize $productSize, Product $product, UpdateSizeProductRequest $request)
    {
        try {
            $data = $request->validated();
            $productSize->update($data);
            Session::flash('success', 'Sửa màu thành công');
            return response()->json([
                'status' => true,
                'route' => route('admin.products_size', $product->id),
            ], 200);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra vui lòng thử lại'
            ], 200);
        }
    }
}
?>