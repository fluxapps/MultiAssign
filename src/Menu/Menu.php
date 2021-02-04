<?php

namespace srag\Plugins\MultiAssign\Menu;

use ilMultiAssignPlugin;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ilUIPluginRouterGUI;
use multaAccess;
use multaMainGUI;
use srag\DIC\MultiAssign\DICTrait;

/**
 * Class Menu
 *
 * @package srag\Plugins\MultiAssign\Menu
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{

    use DICTrait;

    const PLUGIN_CLASS_NAME = ilMultiAssignPlugin::class;


    /**
     * @inheritDoc
     */
    public function getStaticTopItems() : array
    {
        return [
            $this->mainmenu->topParentItem($this->if->identifier(ilMultiAssignPlugin::PLUGIN_ID . "_top"))
                ->withTitle(ilMultiAssignPlugin::PLUGIN_NAME)
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return multaAccess::hasAccess();
                })
        ];
    }


    /**
     * @inheritDoc
     */
    public function getStaticSubItems() : array
    {
        $parent = $this->getStaticTopItems()[0];

        return [
            $this->mainmenu->link($this->if->identifier(ilMultiAssignPlugin::PLUGIN_ID . "_main"))
                ->withParent($parent->getProviderIdentification())
                ->withTitle(self::plugin()->translate("header_title"))
                ->withAction(self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    multaMainGUI::class
                ]))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })
                ->withVisibilityCallable(function () : bool {
                    return multaAccess::hasAccess();
                })
        ];
    }
}
