<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\Admin\CurrencyService;
use App\Http\Requests\Admin\CurrencyRequest;

class CurrencyController extends AdminController
{
	protected $permission = 'settings/currencies';
	protected $view_path = 'admin.currencies.';
	protected $page;
	protected $service;

	public function __construct(CurrencyService $service)
    {
		$this->middleware('auth.permission:' . $this->permission);
		$this->service = $service;
		$this->page = [[$this->permission, 'Settings'], [$this->permission, 'Currencies']];
	}

	public function dtlist()
    {
    	return $this->service->dtData();
    }

    public function showIndex()
    {
        $page = $this->page;
        $dt = $this->service->dtTable();

        return view($this->view_path . 'index', compact('page', 'dt'));
    }

    public function showCreate()
    {
    	$page = $this->page;
    	array_push($page, ['', 'Create']);
    	$entry = $this->service->newEntry(['status' => 1, 'ordering' => $this->service->max('ordering') + 100]);

    	return view($this->view_path . 'manage', compact('page', 'entry'));
    }

    public function showEdit($id)
    {
    	$page = $this->page;
    	array_push($page, ['', 'Edit']);
    	$entry = $this->service->getEntry($id);

    	return view($this->view_path . 'manage', compact('page', 'entry'));
    }

    public function create(CurrencyRequest $request)
    {
    	$this->service->createEntry($request);

        return redirect()->action('Admin\CurrencyController@showIndex');
    }

    public function edit(CurrencyRequest $request, $id)
    {
        $this->service->editEntry($request, $id);

        return redirect()->action('Admin\CurrencyController@showIndex');
    }

    public function delete($id)
    {
    	$entry = $this->service->deleteEntry($id);
    }
}
