<?PHP
declare(strict_types=1);

namespace Scandiweb\Test\Setup\Patch\Data;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\App\State;


class AddSimpleProduct implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var State
     */
    private $appState;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ProductFactory $productFactory,
        CategoryFactory $categoryFactory,
        State $appState
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->productFactory = $productFactory;
        $this->categoryFactory = $categoryFactory;
        $this->appState = $appState;
    }


    public static function getDependencies()
    {
        return [];
    }


    public function getAliases()
    {
        return [];
    }


    public function apply()
    {
            $this->moduleDataSetup->startSetup();

            $this->appState->setAreaCode('adminhtml');

            $categories = $this->categoryFactory->create()
                ->getCollection()
                ->addAttributeToFilter('url_key', ['in' => ['men']])
                ->addAttributeToSelect('entity_id');

            $categoryId = $categories->getFirstItem()->getEntityId();


            $product = $this->productFactory->create();
            $product->setSku('product-assignment');
            $product->setName('Product Assignment');
            $product->setAttributeSetId(4);
            $product->setStatus(1);
            $product->setVisibility(4);
            $product->setTaxClassId(0);
            $product->setTypeId('simple');
            $product->setPrice(100);
            $product->setCategoryIds([$categoryId]);
            $product->setWebsiteIds([1]);
            $product->setStockData(
                                    array(
                                        'use_config_manage_stock' => 0,
                                        'manage_stock' => 1,
                                        'is_in_stock' => 1,
                                        'qty' => 100
                                    )
                                );
            $product->save();

            $this->moduleDataSetup->endSetup();
    }
}