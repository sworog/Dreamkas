package project.lighthouse.autotests.pages.commercialManager.catalog;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class LocalMenuNavigation extends CommonPageObject {

    public LocalMenuNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public NavigationLinkFacade getStartEditionNavigationLink() {
        return new NavigationLinkFacade(this, "Редактировать");
    }

    public NavigationLinkFacade getStopEditionNavigationLink() {
        return new NavigationLinkFacade(this, "Завершить редактирование");
    }

    public NavigationLinkFacade getProductsExportNavigationLink() {
        return new NavigationLinkFacade(this, "Выгрузить в SetRetail");
    }
}
