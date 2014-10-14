package ru.dreamkas.elements.bootstrap.buttons.abstraction;

import org.openqa.selenium.By;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.abstraction.AbstractFacade;
import ru.dreamkas.pages.modal.ModalWindowPage;

public abstract class AbstractBtnFacade extends AbstractFacade {

    public AbstractBtnFacade(ModalWindowPage modalWindowPage, String facadeText) {
        super(modalWindowPage, facadeText);
    }

    public AbstractBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    public AbstractBtnFacade(CommonPageObject pageObject, By customFindBy) {
        super(pageObject, customFindBy);
    }

    @Override
    public String getXpathPattern() {
        return "//*[contains(@class, 'btn btn-" + btnClassName() + "') and normalize-space(text())='%s']";
    }

    public abstract String btnClassName();
}
