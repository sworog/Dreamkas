package project.lighthouse.autotests.elements.bootstrap.buttons;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.pageObjects.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

/**
 * Button facade for bootstrap success button
 */
public class SuccessBtnFacade extends AbstractBtnFacade {

    public SuccessBtnFacade(CommonPageObject pageObject, By customFindBy) {
        super(pageObject, customFindBy);
    }

    @Override
    public String btnClassName() {
        return "success";
    }
}
