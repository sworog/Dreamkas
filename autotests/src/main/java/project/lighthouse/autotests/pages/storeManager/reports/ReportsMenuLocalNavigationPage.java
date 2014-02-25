package project.lighthouse.autotests.pages.storeManager.reports;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

@DefaultUrl("/reports")
public class ReportsMenuLocalNavigationPage extends CommonPageObject {

    public ReportsMenuLocalNavigationPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void grossSalePerHourLinkClick() {
        new NavigationLinkFacade(this, "Продажи по часам").click();
    }

    public void grossSaleByProductsLinkClick() {
        new NavigationLinkFacade(this, "Продажи по группам").click();
    }

    public void storeGrossSaleMarginLinkClick() {
        new NavigationLinkFacade(this, "Валовая прибыль").click();
    }
}
