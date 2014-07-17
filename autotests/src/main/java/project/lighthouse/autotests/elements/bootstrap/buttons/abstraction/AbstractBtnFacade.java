package project.lighthouse.autotests.elements.bootstrap.buttons.abstraction;

import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.abstraction.AbstractFacade;

public abstract class AbstractBtnFacade extends AbstractFacade {

    public AbstractBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String getXpathPattern() {
        return "//button[contains(@class, 'btn btn-" + btnClassName() + "') and contains(text(), '%s')]";
    }

    public abstract String btnClassName();
}
