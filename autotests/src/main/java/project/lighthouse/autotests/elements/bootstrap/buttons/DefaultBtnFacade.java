package project.lighthouse.autotests.elements.bootstrap.buttons;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.objects.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

/**
 * Button facade for bootstrap default button
 */
public class DefaultBtnFacade extends AbstractBtnFacade {

    public DefaultBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    public DefaultBtnFacade(CommonPageObject pageObject, By customFindBy) {
        super(pageObject, customFindBy);
    }

    @Override
    public String btnClassName() {
        return "default";
    }
}
