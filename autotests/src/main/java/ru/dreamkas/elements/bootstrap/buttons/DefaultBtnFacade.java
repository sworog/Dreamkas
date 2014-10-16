package ru.dreamkas.elements.bootstrap.buttons;

import org.openqa.selenium.By;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

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
