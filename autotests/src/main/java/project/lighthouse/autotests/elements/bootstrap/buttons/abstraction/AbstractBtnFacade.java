package project.lighthouse.autotests.elements.bootstrap.buttons.abstraction;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.abstraction.AbstractFacade;

public abstract class AbstractBtnFacade extends AbstractFacade {

    public AbstractBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    public AbstractBtnFacade(CommonPageObject pageObject, By customFindBy) {
        super(pageObject, customFindBy);
    }

    @Override
    public String getXpathPattern() {
        return "//*[contains(@class, 'btn btn-" + btnClassName() + "') and contains(text(), '%s')]";
    }

    public abstract String btnClassName();
}
