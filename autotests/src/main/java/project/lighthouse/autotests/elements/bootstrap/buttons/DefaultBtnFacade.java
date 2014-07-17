package project.lighthouse.autotests.elements.bootstrap.buttons;

import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

/**
 * Button facade for bootstrap default button
 */
public class DefaultBtnFacade extends AbstractBtnFacade {

    public DefaultBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String btnClassName() {
        return "default";
    }
}
