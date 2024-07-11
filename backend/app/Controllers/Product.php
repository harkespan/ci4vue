<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductModel;

class Product extends ResourceController
{
    function __construct()
    {
        $this->pm = new ProductModel();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data = $this->pm->findAll();
        if($data)
        {
            return $this->respond($data);
        }
        else
        {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $data = $this->pm->find(['id' => $id]);
        if($data)
        {
            return $this->respond($data[0]);
        }
        else
        {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }


    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $json = $this->request->getJSON();
        $data = [
            'product_name' => $json->product_name,
            'price' => $json->price,
        ];

        $valid = $this->pm->insert($data);
        if($valid)
        {
            return $this->respondCreated($valid);
        }
        else
        {
            return $this->fail('Data gagal disimpan', 400);
        }
    }


    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $json = $this->request->getJSON();
        $data = [
            'product_name' => $json->product_name,
            'price' => $json->price,
        ];

        $cekId = $this->pm->find(['id' => $id]);
        if(!$cekId)
        {
            return $this->fail('Data tidak ditemukan', 404);
        }
        
        $valid = $this->pm->update($id,$data);
        if($valid)
        {
            return $this->respond($valid);
        }
        else
        {
            return $this->fail('Data gagal diupdate', 400);
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $cekId = $this->pm->find(['id' => $id]);
        if(!$cekId)
        {
            return $this->fail('Data tidak ditemukan', 404);
        }
        
        $valid = $this->pm->delete($id);
        if($valid)
        {
            return $this->respondDeleted("Data berhasil dihapus", 200);
        }
        else
        {
            return $this->fail('Data gagal dihapus', 400);
        }
    }
}
