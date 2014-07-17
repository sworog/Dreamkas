package project.lighthouse.autotests.elements.Buttons;

import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.abstraction.AbstractFacade;

/**
 * Button facade for bootstrap button
 */
public class SuccessButtonFacade extends AbstractFacade {

    public SuccessButtonFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String getXpathPattern() {
        return "//button[@class='btn btn-success' and contains(text(), '%s')]";
    }
}
