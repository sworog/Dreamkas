package project.lighthouse.autotests.elements.bootstrap.buttons;

import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

/**
 * Button facade for bootstrap success button
 */
public class SuccessBtnFacade extends AbstractBtnFacade {

    public SuccessBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String btnClassName() {
        return "success";
    }
}
