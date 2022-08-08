<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Standard;
use App\Models\BookSubCatagory;
use App\Models\Bookcatagory;
use App\Models\Book;
use App\Models\RequestOrder;
use App\Models\RequestBook;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;

class BookController extends Controller
{
    use StudentTrait;
    public function addBookCatagoryEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'catagoryName' => 'required',
            'onlineBooks' => 'required | boolean',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function addBookCatagory(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addBookCatagory'))
        {
            $rules = [
                'catagoryName' => 'required',
                'onlineBooks' => 'required | boolean',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Bookcatagory::where('catagory_name', '=', $input->catagoryName)->count() == 0)
                {
                    $bookcata = new Bookcatagory;
                    $bookcata->catagory_name=$input->catagoryName;
                    $bookcata->online_books_status=$input->onlineBooks;
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                        $output['status']=true;
                        $output['message']='Catagory Successfully Added';
                        $response['data']=$this->encryptData($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=true;
                        $output['message']='Something went wrong. Please try again later.';
                        $response['data']=$this->encryptData($output);
                        $code = 400;
                    }

                }
                else
                {
                    $output['status']=false;
                    $output['message']='Already Exists';
                    $response['data']=$this->encryptData($output);
                    $code = 409;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function deleteCatagory($bookId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('deleteBookCatagory'))
        {
            if (Bookcatagory::where('id', '=', $bookId)->where('deleteStatus',0)->count() == 1)
            {
                Bookcatagory::where('id',$bookId)->update(array('deleteStatus' => 1));
                $output['status'] = true;
                $output['message'] = 'Successfully Deleted';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function getCatagory($bookId)
    {
        // $bookId=$this->decrypt($bookId);
        $user=Auth::User();
        if($user->hasPermissionTo('editBookCatagory'))
        {
            $book = Bookcatagory::where('id',$bookId)->where('deleteStatus',0)->first(['id', 'catagory_name']);

            if (isset($book)) {
                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function getAllCatagory()
    {
        $user=Auth::User();

        if($user->hasPermissionTo('viewBookCatagory'))
        {
            $book = Bookcatagory::where('deleteStatus',0)->where('admin_id',$user->admin_id)->paginate(10,['id', 'catagory_name','online_books_status']);
            if (isset($book)) {

                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function listAllCatagory()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listBookCatagory'))
        {
            $book = Bookcatagory::where('deleteStatus',0)->where('admin_id',$user->admin_id)->get(['id', 'catagory_name','online_books_status']);
            if (isset($book)) {
                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }


        return response($response, $code);
    }
    public function updateCatagoryEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'catagoryName' => 'required',
            'editId' => 'required',
            'onlineBooks' => 'required | boolean',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function updateCatagory(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editBookCatagory'))
        {
            $rules = [
                'catagoryName' => 'required',
                'editId' => 'required',
                'onlineBooks' => 'required | boolean',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Bookcatagory::where('id', '=',$input->editId)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Bookcatagory::where('id',$input->editId)->update(array('catagory_name' => $input->catagoryName,'online_books_status' => $input->onlineBooks));
                    $output['status']=true;
                    $output['message']='Book Catagory Successfully Updated';
                    $response['data']=$this->encryptData($output);
                    $code = 200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='Something went wrong. Please try again later.';
                    $response['data']=$this->encryptData($output);
                    $code = 400;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);

    }

    public function updateSubCatagoryEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'catagoryId' => 'required',
            'subCatagoryName' => 'required',
            'editId' => 'required',
            'onlineBooks' => 'required | boolean',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function updateSubCatagory(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editBookSubCatagory'))
        {
            $rules = [
                'catagoryId' => 'required',
                'subCatagoryName' => 'required',
                'editId' => 'required',
                'onlineBooks' => 'required | boolean',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (BookSubCatagory::where('id', '=',$input->editId)->where('user_id',$user->id)->where('admin_id',$user->admin_id)->where('deleteStatus',0)->count() == 1)
                {
                    BookSubCatagory::where('id',$input->editId)->update(array('catagory_id' => $input->catagoryId,'subcatagory_name' => $input->subCatagoryName));
                    $output['status']=true;
                    $output['message']='Book Sub Catagory Successfully Updated';
                    $response['data']=$this->encryptData($output);
                    $code = 200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='Something went wrong. Please try again later.';
                    $response['data']=$this->encryptData($output);
                    $code = 400;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);

    }
    public function addBookSubCatagoryEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subCatagoryName' => 'required',
            'catagoryId' => 'required',
            'onlineBooks' => 'required | boolean',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function addBookSubCatagory(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addBookSubCatagory'))
        {
            $rules = [
                'subCatagoryName' => 'required',
                'catagoryId' => 'required',
                'onlineBooks' => 'required | boolean',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (BookSubCatagory::where('subcatagory_name', '=', $input->subCatagoryName)->where('catagory_id',$input->catagoryId)->where('admin_id',$user->admin_id)->count() == 0)
                {
                    $bookcata = new BookSubCatagory;
                    $bookcata->subcatagory_name=$input->subCatagoryName;
                    $bookcata->catagory_id=$input->catagoryId;
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                        $output['status']=true;
                        $output['message']='Sub Catagory Successfully Added';

                        $response['data']=$this->encryptData($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=true;
                        $output['message']='Something went wrong. Please try again later.';
                        $response['data']=$this->encryptData($output);
                        $code = 400;
                    }

                }
                else
                {
                    $output['status']=false;
                    $output['message']='Already Exists';
                    $response['data']=$this->encryptData($output);
                    $code = 409;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function deleteBookSubCatagory($subCatagoryId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('deleteBookSubCatagory'))
        {
            if (BookSubCatagory::where('id', '=', $subCatagoryId)->where('deleteStatus',0)->count() == 1)
            {
                BookSubCatagory::where('id',$subCatagoryId)->update(array('deleteStatus' => 1));
                $output['status'] = true;
                $output['message'] = 'Successfully Deleted';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function getBookSubCatagory($subCatagoryId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editBookSubCatagory'))
        {
            $booksubcatagory = BookSubCatagory::where('id',$subCatagoryId)->where('deleteStatus',0)->first(['encrypt_id AS subcatagory_id', 'subcatagory_name']);
            if (isset($booksubcatagory)) {
                $output['status'] = true;
                $output['response'] = $booksubcatagory;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function listAllSubCatagory()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listBookSubCatagory'))
        {
            $BookSubCatagory = BookSubCatagory::where('deleteStatus',0)->where('user_id',$user->id)->get(['id', 'subcatagory_name','online_books_status']);
            if (isset($BookSubCatagory)) {
                $output['status'] = true;
                $output['response'] = $BookSubCatagory;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }


        return response($response, $code);
    }
    public function updateBookEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subCatagoryId' => 'required',
                'catagoryId' => 'required',
                'bookName' => 'required',
                'isbnNO' => 'required',
                'authorName' => 'required',
                'onlineBooks' => 'required | boolean',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function updateBook(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('editBooks'))
        {
            $rules = [
                'subCatagoryId' => 'required',
                'catagoryId' => 'required',
                'bookName' => 'required',
                'isbnNO' => 'required',
                'authorName' => 'required',
                'onlineBooks' => 'required | boolean',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Book::where('id', '=',$input->editId)->where('admin_id',$user->admin_id)->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Book::where('id',$input->editId)->update(array('catagory_id' => $input->catagoryId,'subcatagory_id' => $input->subCatagoryId,'book_name' => $input->bookName,'isbn_no' => $input->isbn_no,'author_name' => $input->author_name));
                    $output['status']=true;
                    $output['message']='Book Successfully Updated';
                    $response['data']=$this->encryptData($output);
                    $code = 200;
                }
                else
                {
                    $output['status']=true;
                    $output['message']='Something went wrong. Please try again later.';
                    $response['data']=$this->encryptData($output);
                    $code = 400;
                }

            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function addBookEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subCatagoryId' => 'required',
                'catagoryId' => 'required',
                'bookName' => 'required',
                'isbnNO' => 'required',
                'authorName' => 'required',
                'onlineBooks' => 'required | boolean',
        ]);

        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function addBook(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addBooks'))
        {
            $rules = [
                'subCatagoryId' => 'required',
                'catagoryId' => 'required',
                'bookName' => 'required',
                'isbnNO' => 'required',
                'authorName' => 'required',
                'onlineBooks' => 'required | boolean',
                'url' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Book::where('book_name', '=', $input->bookName)->where('catagory_id',$input->catagoryId)->where('online_books_status',$input->onlineBooks)->where('subcatagory_id',$input->subCatagoryId)->where('admin_id',$user->admin_id)->count() == 0)
                {
                    $bookcata = new Book;
                    $bookcata->subcatagory_id=$input->subCatagoryId;
                    $bookcata->isbn_no=$input->isbnNO;
                    $bookcata->online_books_status=$input->onlineBooks;
                    $bookcata->url=$input->url;
                    $bookcata->book_name=$input->bookName;
                    $bookcata->author_name=$input->authorName;
                    $bookcata->catagory_id=$input->catagoryId;
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                        $output['status']=true;
                        $output['message']='Book Successfully Added';

                        $response['data']=$this->encryptData($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=true;
                        $output['message']='Something went wrong. Please try again later.';
                        $response['data']=$this->encryptData($output);
                        $code = 400;
                    }

                }
                else
                {
                    $output['status']=false;
                    $output['message']='Already Exists';
                    $response['data']=$this->encryptData($output);
                    $code = 409;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function deleteBook($bookId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('deleteBooks'))
        {
            if (Book::where('id', '=', $bookId)->where('deleteStatus',0)->where('admin_id',$user->admin_id)->count() == 1)
            {
                Book::where('id',$bookId)->update(array('deleteStatus' => 1));
                $output['status'] = true;
                $output['message'] = 'Successfully Deleted';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function getBook($bookId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editBooks'))
        {
            $book = Book::with(['catagory','subcatagory'])->where('deleteStatus','=',0)->where('id',$bookId)->get();

            if (isset($book)) {
                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function getAllBooks()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('getBooks'))
        {
            $book = Book::with(['catagory','subcatagory'])->where('deleteStatus','=',0)->where('admin_id',$user->admin_id)->paginate(10);
            if (isset($book)) {
                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function listAllBooks()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('listBooks'))
        {
            $book = Book::where('deleteStatus',0)->where('admin_id',$user->admin_id)->get(['id', 'book_name','online_books_status']);
            if (isset($book)) {
                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }


        return response($response, $code);
    }
    public function getAllorders()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('getAllorders'))
        {
            $book = RequestBook::with(['classes','student','book','catagory','subcatagory','staff'])->where('deleteStatus','=',0)->where('admin_id','=',$user->admin_id)->get();

            if (isset($book)) {

                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function searchStaff($staffId)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('searchOrders'))
        {
            $book = RequestBook::with(['classes','student','book','catagory','subcatagory','staff'])->where('deleteStatus','=',0)->where('staff_id','=',$staffId)->where('admin_id','=',$user->admin_id)->get();

            if (isset($book)) {

                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function toReturnEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bookId' => 'required',
            'returned_status' => 'required',
            'fine_amount' => 'required',
            'fine_status' => 'required',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function toReturn(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('ReturnPermission'))
        {
            $rules = [
                'bookId' => 'required',
                'returned_status' => 'required',
                'fine_amount' => 'required',
                'fine_status' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (RequestBook::where('id', '=', $input->bookId)->where('deleteStatus','=',0)->count() == 1)
                {
                     RequestBook::where('id',$input->bookId)->update(array('returned_date' => date('Y-m-d'),'returned_status' => $input->returned_status,'fine_amount' => $input->fine_amount,'fine_status' => $input->fine_status));
                        $output['status']=true;
                        $output['message']='Returned Successfully';
                        $response['data']=$this->encryptData($output);
                        $code = 200;

                }
                else
                {
                    $output['status']=false;
                    $output['message']='Request Book Not Occured';
                    $response['data']=$this->encryptData($output);
                    $code = 409;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function todayReturnList()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('searchOrders'))
        {
            $book = RequestBook::with(['classes','student','book','catagory','subcatagory','staff'])->where('deleteStatus','=',0)->where('return_date','=',date('Y-m-d'))->where('admin_id','=',$user->admin_id)->get();

            if (isset($book))
            {
                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function searchStudent($studentId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('searchOrders'))
        {
            $book = RequestBook::with(['classes','student','book','catagory','subcatagory','staff'])->where('deleteStatus','=',0)->where('student_id','=',$studentId)->where('admin_id','=',$user->admin_id)->get();

            if (isset($book)) {

                $output['status'] = true;
                $output['response'] = $book;
                $output['message'] = 'Successfully Retrieved';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function deleteBookRecords($bookId)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('deleteBooksRecords'))
        {
            if (RequestBook::where('id', '=', $bookId)->where('deleteStatus',0)->count() == 1)
            {
                RequestBook::where('id',$bookId)->update(array('deleteStatus' => 1));
                $output['status'] = true;
                $output['message'] = 'Successfully Deleted';
                $response['data']=$this->encryptData($output);
                $code=200;
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'No Records Found';
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }
    public function request_booksEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'catagoryId' => 'required',
            'subCatagoryId' => 'required',
            'bookId' => 'required',
            'studentId' => 'required',
            'classId' => 'required',
            'staffId' => 'required',
            'getDate' => 'required | date',
            'returnDate' => 'required | date',
            'student' => 'required | boolean',
        ]);


        if(!$validator->fails())
        {
            $response['data']=$this->encryptData(json_encode($request->all()));
            //$response=$this->encrypt($output);
            $code = 200;
        }
        else
        {
            $response['message']=[$validator->errors()->first()];
        // $response=$this->encrypt($output);
            $code = 200;
        }
        return response($response, $code);
    }
    public function request_books(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('request_books'))
        {
            $rules = [
                'catagoryId' => 'required',
                'subCatagoryId' => 'required',
                'bookId' => 'required',
                'studentId' => 'required',
                'classId' => 'required',
                'staffId' => 'required',
                'getDate' => 'required | date',
                'returnDate' => 'required | date',
                'student' => 'required | boolean',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if($input->student == true)
                {
                    $counting=RequestBook::where('student_id', '=', $input->studentId)->where('returned_status',0)->where('admin_id',$user->admin_id)->count();
                }
                else
                {
                    $counting=RequestBook::where('staff_id', '=', $input->staffId)->where('returned_status',0)->where('admin_id',$user->admin_id)->count();
                }
                if ($counting == 0)
                {
                    $bookcata = new RequestBook;
                    $bookcata->student_id=$input->studentId;
                    $bookcata->class_id=$input->classId;
                    $bookcata->staff_id=$input->staffId;
                    $bookcata->catagory_id=$input->catagoryId;
                    $bookcata->book_id=$input->bookId;
                    $bookcata->subcatagory_id=$input->subCatagoryId;
                    $bookcata->get_date=date('Y-m-d',strtotime($input->getDate));
                    $bookcata->return_date=date('Y-m-d',strtotime($input->returnDate));
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                        $output['status']=true;
                        $output['message']='Requested Books Added';
                        $response['data']=$this->encryptData($output);
                        $code = 200;
                    }
                    else
                    {
                        $output['status']=true;
                        $output['message']='Something went wrong. Please try again later.';
                        $response['data']=$this->encryptData($output);
                        $code = 400;
                    }

                }
                else
                {
                    $output['status']=false;
                    $output['message']='Already Exists';
                    $response['data']=$this->encryptData($output);
                    $code = 409;
                }
            }
            else
            {
                $output['status']=false;
                $output['message']=[$validator->errors()->first()];
                $response['data']=$this->encryptData($output);
                $code=400;
            }
        }
        else
        {
            $output['status']=false;
            $output['message']='Unauthorized Access';
            $response['data']=$this->encryptData($output);
            $code=400;
        }
        return response($response, $code);
    }

}
