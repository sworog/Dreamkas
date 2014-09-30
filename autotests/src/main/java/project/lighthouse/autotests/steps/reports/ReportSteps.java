package project.lighthouse.autotests.steps.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.reports.ReportsMainPage;

public class ReportSteps extends ScenarioSteps {

    ReportsMainPage reportsMainPage;

    @Step
    public void clickOnStockBalanceReport() {
        reportsMainPage.clickOnStockBalanceReport();
    }
}
