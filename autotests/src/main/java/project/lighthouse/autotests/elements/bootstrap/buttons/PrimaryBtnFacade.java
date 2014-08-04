package project.lighthouse.autotests.elements.bootstrap.buttons;

import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

/**
 * Button facade for bootstrap primary button
 */
public class PrimaryBtnFacade extends AbstractBtnFacade {

    public PrimaryBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    public PrimaryBtnFacade(CommonPageObject pageObject, String buttonText, String modalWindowXpath) {
        super(pageObject, buttonText, modalWindowXpath);
    }

    @Override
    public String btnClassName() {
        return "primary";
    }
}
