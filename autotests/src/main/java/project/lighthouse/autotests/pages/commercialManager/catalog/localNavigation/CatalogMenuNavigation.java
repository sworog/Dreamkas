package project.lighthouse.autotests.pages.commercialManager.catalog.localNavigation;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class CatalogMenuNavigation extends CommonPageObject {

    public CatalogMenuNavigation(WebDriver driver) {
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
