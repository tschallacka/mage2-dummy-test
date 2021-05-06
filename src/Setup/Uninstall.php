<?php namespace Test\Dummy\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class Uninstall implements UninstallInterface
{
    protected $eavSetupFactory;
    protected $output;

    public function __construct(EavSetupFactory $eavSetupFactory, ConsoleOutput $output)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->output = $output;
    }

    /**
     * @inheritDoc
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // Sanity check
        $auth = BP . DIRECTORY_SEPARATOR . 'auth.json';
        if(!file_exists($auth)) {
            $this->output->writeln("<fg=red>NO AUTH.JSON FOUND IN \"$auth\"</>");
            $this->output->writeln("<fg=yellow>In the event that this process hang indefinitely on 'Removing code from Magento CodeBase:'</>");
            $this->output->writeln("<fg=yellow>Create in your Magento root folder a auth.json copied from auth.json.sample. </>");
            $this->output->writeln("<fg=yellow>Enter in that file your repo.magento.com and other access keys. </>");
            $this->output->writeln("<fg=red>Do not forget to add auth.json to your .gitignore to prevent leaking your access keys!!!</>");
        }
        // Uninstall code
        $eavSetup = $this->eavSetupFactory->create();
        $this->output->writeln("Starting to remove the attributes for dropship.");
        $eavSetup->removeAttribute(Product::ENTITY, UpgradeData::DROPSHIP_ATTRIBUTE);
    }
}
