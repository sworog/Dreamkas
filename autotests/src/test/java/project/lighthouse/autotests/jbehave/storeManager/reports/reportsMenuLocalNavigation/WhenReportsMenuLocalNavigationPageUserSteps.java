package project.lighthouse.autotests.jbehave.storeManager.reports.reportsMenuLocalNavigation;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.storeManager.reports.ReportsMenuLocalNavigationPageSteps;

public class WhenReportsMenuLocalNavigationPageUserSteps {

    @Steps
    ReportsMenuLocalNavigationPageSteps reportsMenuLocalNavigationPageSteps;

    @When("the user clicks on gross sale by hour report link")
    public void whenTheUserClicksOnGrossSaleByHourReportLink() {
        reportsMenuLocalNavigationPageSteps.grossSalePerHourLinkClick();
    }

    @When("the user clicks on gross sale by products report link")
    public void whenTheUserClicksOnGrossSaleByProductsReportLink() {
        reportsMenuLocalNavigationPageSteps.grossSaleByProductsLinkClick();
    }

    @When("the user clicks on store gross sale margin report link")
    public void whenTheUserClicksOnStoreGrossSaleMarginReportLink() {
        reportsMenuLocalNavigationPageSteps.storeGrossSaleMarginLinkClick();
    }
}
