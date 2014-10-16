package ru.dreamkas.elements.bootstrap.buttons;

import org.openqa.selenium.By;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.buttons.abstraction.AbstractBtnFacade;

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
