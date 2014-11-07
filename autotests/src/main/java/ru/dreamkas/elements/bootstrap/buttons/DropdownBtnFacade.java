package ru.dreamkas.elements.bootstrap.buttons;

import org.openqa.selenium.By;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.abstraction.AbstractFacade;

public class DropdownBtnFacade extends AbstractFacade {

    public DropdownBtnFacade(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String getXpathPattern() {
        return "//*[contains(@class, 'dropdown-toggle') and normalize-space(text())='%s']";
    }

    public void clickDropdownItem(String label) {
        String xpath = String.format(
                "%s/../ul//*[contains(@class, '%s') and normalize-space(text())='%s']",
                getXpathPatternWithFacadeText(),
                "dropdown-menu-item",
                label
        );
        click();
        getPageObject().getCommonActions().elementClick(By.xpath(xpath));
    }
}
