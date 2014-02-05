package project.lighthouse.autotests.steps.commercialManager.reports;

import net.thucydides.core.annotations.Step;
import project.lighthouse.autotests.pages.commercialManager.reports.ReportsMenuLocalNavigationPage;

public class ReportsMenuLocalNavigationPageSteps {

    ReportsMenuLocalNavigationPage reportsMenuLocalNavigationPage;

    @Step
    public void grossSalesByStoresLinkClick() {
        reportsMenuLocalNavigationPage.grossSalesByStoresLinkClick();
    }

    @Step
    public void grossSaleMarginLinkClick() {
        reportsMenuLocalNavigationPage.grossSaleMarginLinkClick();
    }
}
