package project.lighthouse.autotests.steps.storeManager.reports;

import net.thucydides.core.annotations.Step;
import project.lighthouse.autotests.pages.departmentManager.reports.ReportsMainPage;

public class ReportsMainPageSteps {

    ReportsMainPage reportsMainPage;

    @Step
    public void grossSalePerHourLinkClick() {
        reportsMainPage.grossSalePerHourLinkClick();
    }

    @Step
    public void grossSaleByProductsLinkClick() {
        reportsMainPage.grossSaleByProductsLinkClick();
    }
}
