package project.lighthouse.autotests.pages.commercialManager.reports;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class ReportsMenuLocalNavigationPage extends CommonPageObject {

    public ReportsMenuLocalNavigationPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void grossSalesByStoresLinkClick() {
        new NavigationLinkFacade(getDriver(), "Продажи по магазинам").click();
    }

    public void grossSaleMarginLinkClick() {
        new NavigationLinkFacade(getDriver(), "Валовая прибыль").click();
    }
}
