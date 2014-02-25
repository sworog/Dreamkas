package project.lighthouse.autotests.steps.commercialManager.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.commercialManager.reports.ReportsMenuLocalNavigationPage;

public class ReportsMenuLocalNavigationPageSteps extends ScenarioSteps {

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
