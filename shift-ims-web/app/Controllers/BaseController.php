<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * @var BaseConnection
     */
    protected $db;

    /**
     * @var array
     */
    protected $models;

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['all'];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->db = db_connect();

        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    /**
     * 
     * @return \App\Models\PartyModel
     */
    public function getPartyModel()
    {
        if (!isset($this->models['party'])) {
            $this->models['party'] = new \App\Models\PartyModel($this->db);
        }

        return $this->models['party'];
    }

    /**
     * 
     * @return \App\Models\ProductCategoryModel
     */
    public function getProductCategoryModel()
    {
        if (!isset($this->models['product_category'])) {
            $this->models['product_category'] = new \App\Models\ProductCategoryModel($this->db);
        }

        return $this->models['product_category'];
    }

    /**
     * 
     * @return \App\Models\ProductModel
     */
    public function getProductModel()
    {
        if (!isset($this->models['product'])) {
            $this->models['product'] = new \App\Models\ProductModel($this->db);
        }

        return $this->models['product'];
    }

    /**
     * 
     * @return \App\Models\ServiceOrderModel
     */
    public function getServiceOrderModel()
    {
        if (!isset($this->models['service_order'])) {
            $this->models['service_order'] = new \App\Models\ServiceOrderModel($this->db);
        }

        return $this->models['service_order'];
    }

        /**
     * 
     * @return \App\Models\StockUpdateModel
     */
    public function getStockUpdateModel()
    {
        if (!isset($this->models['stock_update'])) {
            $this->models['stock_update'] = new \App\Models\StockUpdateModel($this->db);
        }

        return $this->models['stock_update'];
    }

    /**
     * 
     * @return \App\Models\StockAdjustmentModel
     */
    public function getStockAdjustmentModel()
    {
        if (!isset($this->models['stock_adjustment'])) {
            $this->models['stock_adjustment'] = new \App\Models\StockAdjustmentModel($this->db);
        }

        return $this->models['stock_adjustment'];
    }

        /**
     * 
     * @return \App\Models\StockUpdateDetailModel
     */
    public function getStockUpdateDetailModel()
    {
        if (!isset($this->models['stock_update_detail'])) {
            $this->models['stock_update_detail'] = new \App\Models\StockUpdateDetailModel($this->db);
        }

        return $this->models['stock_update_detail'];
    }
}
