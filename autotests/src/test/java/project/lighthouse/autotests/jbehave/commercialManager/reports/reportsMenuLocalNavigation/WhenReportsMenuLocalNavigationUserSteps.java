package project.lighthouse.autotests.jbehave.commercialManager.reports.reportsMenuLocalNavigation;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.commercialManager.reports.ReportsMenuLocalNavigationPageSteps;

public class WhenReportsMenuLocalNavigationUserSteps {

    @Steps
    ReportsMenuLocalNavigationPageSteps reportsMenuLocalNavigationPageSteps;

    @When("the user clicks on gross sale by stores report link")
    public void whenTheUserClicksOnGrossSaleByHourReportLink() {
        reportsMenuLocalNavigationPageSteps.grossSalesByStoresLinkClick();
    }

    @When("the user clicks on gross margin by days report link")
    public void whenTheUserClicksOnGrossSaleByProductsReportLink() {
        reportsMenuLocalNavigationPageSteps.grossSaleMarginLinkClick();
    }
}
