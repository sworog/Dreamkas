package ru.dreamkas.elements.bootstrap.buttons;

import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

/**
 * Button facade for bootstrap default button
 */
public class TransparentBtnFacade extends AbstractBtnFacade {

    public TransparentBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String btnClassName() {
        return "transparent";
    }
}
