<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\user;
use App\Models\standard;
use App\Models\BookSubCatagory;
use App\Models\bookcatagory;
use App\Models\book;
use App\Models\RequestOrder;
use App\Models\RequestBook;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\StudentTrait;

class BookController extends Controller
{
    public function addBookCatagory(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addBookCatagory'))
        {
            $rules = [
                'catagoryName' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Bookcatagory::where('catagory_name', '=', $input->catagoryName)->count() == 0)
                {
                    $bookcata = new Bookcatagory;
                    $bookcata->catagory_name=$input->catagoryName;
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                       $secId=$this->encryptData($section->id);
                       Section::where('id',$section->id)->update(array('encrypt_id' => $secId));
                       $sectionObject = Section::where('id',$section->id)->first(['encrypt_id AS section_id', 'section_name']);
                        $output['status']=true;
                        $output['message']='Section Successfully Added';
                        $output['response']=$sectionObject;
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
        $bookId=$this->decrypt($bookId);
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
        $bookId=$this->decrypt($bookId);
        $user=Auth::User();
        if($user->hasPermissionTo('editBookCatagory'))
        {
            $book = Bookcatagory::where('id',$bookId)->where('deleteStatus',0)->first(['encrypt_id AS catagory_id', 'catagory_name']);
            if (isset($book->book_id)) {
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
           // $section = Section::all(['encrypt_id AS section_id', 'section_name']);
            $book = Bookcatagory::where('deleteStatus',0)->where('admin_id',$user->admin_id)->paginate(10,['encrypt_id AS catagory_id', 'catagory_name']);
            if (isset($book->book_id)) {

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
            $book = Bookcatagory::where('deleteStatus',0)->where('admin_id',$user->admin_id)->get(['encrypt_id AS catagory_id', 'catagory_name']);
            if (isset($book->catagory_id)) {
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
    public function updateCatagory(Request $request)
    {

        $user=Auth::User();
        if($user->hasPermissionTo('editBookCatagory'))
        {
            $rules = [
                'sectionName' => 'required',
                'active' => 'required',
                'sectionId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Section::where('id', '=', $this->decrypt($input->sectionId))->where('user_id',$user->id)->where('deleteStatus',0)->count() == 1)
                {
                    Section::where('id',$this->decrypt($input->sectionId))->update(array('section_name' => $input->sectionName,'active' => $input->active));
                    $output['status']=true;
                    $output['message']='Section Successfully Updated';
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



    public function addBookSubCatagory(Request $request)
    {
        $user=Auth::User();
        if($user->hasPermissionTo('addBookSubCatagory'))
        {
            $rules = [
                'subCatagoryName' => 'required',
                'catagoryId' => 'required',
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (BookSubCatagory::where('subcatagory_name', '=', $input->subCatagoryName)->where('catagory_id',$input->catagoryId)->where('admin_id',$user->admin_id)->count() == 0)
                {
                    $bookcata = new BookSubCatagory;
                    $bookcata->subcatagory_name=$input->subCatagoryName;
                    $bookcata->catagory_id=$this->decrypt($input->catagoryId);
                    $bookcata->encrypt_catagory_id=$input->catagoryId;
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                       $book_id=$this->encryptData($bookcata->id);
                       BookSubCatagory::where('id',$bookcata->id)->update(array('encrypt_id' => $book_id));
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
        $subCatagoryId=$this->decrypt($subCatagoryId);
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
        $subCatagoryId=$this->decrypt($subCatagoryId);
        $user=Auth::User();
        if($user->hasPermissionTo('editBookSubCatagory'))
        {
            $booksubcatagory = BookSubCatagory::where('id',$subCatagoryId)->where('deleteStatus',0)->first(['encrypt_id AS subcatagory_id', 'subcatagory_name']);
            if (isset($booksubcatagory->subcatagory_id)) {
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
            $BookSubCatagory = BookSubCatagory::where('deleteStatus',0)->where('user_id',$user->id)->get(['encrypt_id AS subcatagory_id', 'subcatagory_name']);
            if (isset($BookSubCatagory->subcatagory_id)) {
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
            ];
            $input=$this->decrypt($request->input('input'));
            $validator = Validator::make((array)$input, $rules);
            if(!$validator->fails())
            {
                if (Book::where('book_name', '=', $input->bookName)->where('catagory_id',$input->catagoryId)->where('subcatagory_id',$input->subCatagoryId)->where('admin_id',$user->admin_id)->count() == 0)
                {
                    $bookcata = new Book;
                    $bookcata->encrypt_subcatagory_id=$input->subCatagoryId;
                    $bookcata->subcatagory_id=$this->decrypt($input->subCatagoryId);
                    $bookcata->encrypt_catagory_id=$input->catagoryId;
                    $bookcata->isbn_no=$input->isbnNO;
                    $bookcata->author_name=$input->authorName;
                    $bookcata->catagory_id=$this->decrypt($input->catagory_id);
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                       $book_id=$this->encryptData($bookcata->id);
                       Book::where('id',$bookcata->id)->update(array('encrypt_id' => $book_id));
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
        $bookId=$this->decrypt($bookId);
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
        $bookId=$this->decrypt($bookId);
        $user=Auth::User();
        if($user->hasPermissionTo('editBooks'))
        {
            $book = Book::with(['catagory','subcatagory'])->where('deleteStatus','=',0)->where('id',$bookId)->get();

            if (isset($book->id)) {
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
            if (isset($book->id)) {
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
            $book = Book::where('deleteStatus',0)->where('admin_id',$user->admin_id)->get(['encrypt_id AS book_id', 'book_name']);
            if (isset($book->book_id)) {
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

            if (isset($book->id)) {

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
        $staffId=$this->decrypt($staffId);
        $user=Auth::User();
        if($user->hasPermissionTo('searchOrders'))
        {
            $book = RequestBook::with(['classes','student','book','catagory','subcatagory','staff'])->where('deleteStatus','=',0)->where('staff_id','=',$staffId)->where('admin_id','=',$user->admin_id)->get();

            if (isset($book->id)) {

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
    public function toReturn($bookId)
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
                if (RequestBook::where('id', '=', $this->decrypt($input->bookId))->where('deleteStatus','=',0)->count() == 1)
                {


                     RequestBook::where('id',$this->decrypt($input->bookId))->update(array('returned_date' => date('Y-m-d'),'returned_status' => $input->returned_status,'fine_amount' => $input->fine_amount,'fine_status' => $input->fine_status));
                        $output['status']=true;
                        $output['message']='Returned Successfully';
                        $response['data']=$this->encryptData($output);
                        $code = 200;

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
    public function todayReturnList()
    {
        $user=Auth::User();
        if($user->hasPermissionTo('searchOrders'))
        {
            $book = RequestBook::with(['classes','student','book','catagory','subcatagory','staff'])->where('deleteStatus','=',0)->where('return_date','=',date('Y-m-d'))->where('admin_id','=',$user->admin_id)->get();

            if (isset($book->id)) {

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
        $studentId=$this->decrypt($studentId);
        $user=Auth::User();
        if($user->hasPermissionTo('searchOrders'))
        {
            $book = RequestBook::with(['classes','student','book','catagory','subcatagory','staff'])->where('deleteStatus','=',0)->where('student_id','=',$studentId)->where('admin_id','=',$user->admin_id)->get();

            if (isset($book->id)) {

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
        $bookId=$this->decrypt($bookId);
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
                    $counting=RequestBook::where('student_id', '=', $this->decrypt($input->studentId))->where('returned_status',0)->where('admin_id',$user->admin_id)->count();
                }
                else
                {
                    $counting=RequestBook::where('staff_id', '=', $this->decrypt($input->staffId))->where('returned_status',0)->where('admin_id',$user->admin_id)->count();
                }
                if ($counting == 0)
                {
                    $bookcata = new RequestBook;
                    $bookcata->student_id=$this->decrypt($input->studentId);
                    $bookcata->encrypt_student_id=$input->studentId;
                    $bookcata->class_id=$this->decrypt($input->classId);
                    $bookcata->encrypt_class_id=$input->classId;
                    $bookcata->staff_id=$this->decrypt($input->staffId);
                    $bookcata->encrypt_staff_id=$input->staffId;
                    $bookcata->catagory_id=$this->decrypt($input->catagoryId);
                    $bookcata->encrypt_catagory_id=$input->catagoryId;
                    $bookcata->book_id=$this->decrypt($input->bookId);
                    $bookcata->encrypt_book_id=$input->bookId;
                    $bookcata->subcatagory_id=$this->decrypt($input->subCatagoryId);
                    $bookcata->encrypt_subcatagory_id=$input->subCatagoryId;
                    $bookcata->get_date=date('Y-m-d',strtotime($input->getDate));
                    $bookcata->return_date=date('Y-m-d',strtotime($input->returnDate));
                    $bookcata->user_id=$user->id;
                    $bookcata->admin_id=$user->admin_id;
                    if($bookcata->save())
                    {
                       $book_id=$this->encryptData($bookcata->id);
                       RequestBook::where('id',$bookcata->id)->update(array('encrypt_id' => $book_id));
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

}
