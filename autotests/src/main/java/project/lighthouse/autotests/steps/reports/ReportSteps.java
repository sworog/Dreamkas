package project.lighthouse.autotests.steps.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.pages.reports.ReportsMainPage;
import project.lighthouse.autotests.storage.Storage;

public class ReportSteps extends ScenarioSteps {

    ReportsMainPage reportsMainPage;

    @Step
    public void clickOnStockBalanceReport() {
        reportsMainPage.clickOnStockBalanceReport();
    }

    @Step
    public void openStockBalanceReportsPage(String storeName) {
        String storeId = Storage.getCustomVariableStorage().getStores().get(storeName).getId();
        String posUrl = String.format("%s/reports/stockBalance?storeId=%s", UrlHelper.getWebFrontUrl(), storeId);
        reportsMainPage.getDriver().navigate().to(posUrl);
    }
}
