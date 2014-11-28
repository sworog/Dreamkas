package ru.dreamkas.elements.bootstrap.buttons;

import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;
import ru.dreamkas.pages.modal.ModalWindowPage;

/**
 * Button facade for bootstrap default button
 */
public class TransparentBtnFacade extends AbstractBtnFacade {

    public TransparentBtnFacade(ModalWindowPage modalWindowPage, String facadeText) {
        super(modalWindowPage, facadeText);
    }

    @Override
    public String btnClassName() {
        return "transparent";
    }
}
