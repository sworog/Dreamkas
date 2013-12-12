package project.lighthouse.autotests.jbehave.storeManager.reports.reportsMainPage;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.storeManager.reports.ReportsMainPageSteps;

public class ReportsMainPageUserSteps {

    @Steps
    ReportsMainPageSteps reportsMainPageSteps;

    @When("the user clicks on gross sale by hour report link")
    public void whenTheUserClicksOnGrossSaleByHourReportLink() {
        reportsMainPageSteps.grossSalePerHourLinkClick();
    }

    @When("the user clicks on gross sale by products report link")
    public void whenTheUserClicksOnGrossSaleByProductsReportLink() {
        reportsMainPageSteps.grossSaleByProductsLinkClick();
    }
}
