package ru.dreamkas.elements.bootstrap.buttons;

import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;
import ru.dreamkas.pages.modal.ModalWindowPage;

/**
 * Button facade for bootstrap primary button
 */
public class PrimaryBtnFacade extends AbstractBtnFacade {

    public PrimaryBtnFacade(ModalWindowPage modalWindowPage, String facadeText) {
        super(modalWindowPage, facadeText);
    }

    public PrimaryBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String btnClassName() {
        return "primary";
    }
}
