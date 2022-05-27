<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

  public function index(Request $request)
  {
    if ($request->ajax()) {
      if (!empty($request->input('search.value'))) {
        $search = $request->input('search.value');
      } else {
        $search = '';
      }
      $columns = array(
        0 => 'id',
        1 => 'first_name',
        2 => 'email',
        3 => 'deleted_at',
        4 => 'created_at',
        5 => 'id',
      );

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');
      $totalData = User::where('role_id', 1)->withTrashed()->when($search != '', function ($query) use ($search) {
        $query->where('first_name', 'LIKE', "%{$search}%");
      })->count();
      $users = User::where('role_id', 1)->withTrashed()->when($search != '', function ($query) use ($search) {
        $query->where('first_name', 'LIKE', "%{$search}%");
      })->when($limit != "-1", function ($query) use ($limit, $start) {
        $query->offset($start)->limit($limit);
      })->orderBy($order, $dir)->get();
      $totalFiltered = $totalData;
      $data = array();
      if (!empty($users)) {
        foreach ($users as $user) {

          $show = route('users.show', $user->id);
          $destroy = route('users.destroy', $user->id);
          $restore = route('user.restore', $user->id);

          $csrf = csrf_token();

          $nestedData['id'] = $user->id;
          $nestedData['first_name'] = $user->first_name;
          $nestedData['email'] = $user->email;
          $nestedData['deleted_at'] = '';
          if ($user->trashed()) {
            $nestedData['deleted_at'] .= "Disabled";
          } else {
            $nestedData['deleted_at'] .= "Active";
          }
          $nestedData['created_at'] = date('j M Y h:i a', strtotime($user->created_at));
          $nestedData['action'] = '';
          if ($user->trashed()) {
            $nestedData['action'] .= "<form action='{$restore}' method='POST'>
                                              <input type='hidden' value='{$csrf}' name='_token'>
                                              <button type='submit' data-title='Restore' class='btn btn-success btn-circle'>
                                              <i class='fas fa-trash-restore-alt'></i>
                                              </button>
                                              </form>";
          } else {
            $nestedData['action'] .= "<form action='{$destroy}' method='POST'>
                                              <input type='hidden' name='_method' value='delete' />
                                              <input type='hidden' value='{$csrf}' name='_token'>
                                              <button type='submit' data-title='Disable' class='btn btn-danger btn-circle'>
                                              <i class='fas fa-trash-alt'></i>
                                              </button>
                                              </form>";
          }
          $nestedData['action'] .= "<a data-title='Show' href='{$show}' class='btn btn-success btn-circle'><i class='fas fa-eye'></i></a>";

          $data[] = $nestedData;
        }
        $json_data = array(
          "draw" => intval($request->input('draw')),
          "recordsTotal" => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data" => $data
        );
        echo json_encode($json_data);
      }
    } else {
      return view('Backend.users.index');
    }
  }

  public function show($id)
  {
    $user = User::withTrashed()->whereId($id)->first();
    return view('Backend.users.show', compact('user'));
  }

  public function destroy($id)
  {
    $user = User::whereId($id)->first();
    if ($user->delete()) {
      return redirect()->back()->with(['success' => 'User Deleted Successfully..!']);
    } else {
      return redirect()->back()->with(['error' => 'Something went wrong....!']);
    }
  }

  public function restore($id)
  {
    $user = User::withTrashed()->whereId($id)->first();
    if ($user->restore()) {
      return redirect()->back()->with(['success' => 'User Restored Successfully..!']);
    } else {
      return redirect()->back()->with(['error' => 'Something went wrong....!']);
    }
  }
}
