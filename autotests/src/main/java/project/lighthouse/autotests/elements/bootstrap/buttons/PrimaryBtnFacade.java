package project.lighthouse.autotests.elements.bootstrap.buttons;

import project.lighthouse.autotests.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Button facade for bootstrap primary button
 */
public class PrimaryBtnFacade extends AbstractBtnFacade {

    public PrimaryBtnFacade(ModalWindowPage modalWindowPage, String facadeText) {
        super(modalWindowPage, facadeText);
    }

    @Override
    public String btnClassName() {
        return "primary";
    }
}
