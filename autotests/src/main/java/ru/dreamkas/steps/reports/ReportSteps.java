package ru.dreamkas.steps.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apihelper.UrlHelper;
import ru.dreamkas.pages.reports.ReportsMainPage;

public class ReportSteps extends ScenarioSteps {

    ReportsMainPage reportsMainPage;

    @Step
    public void clickOnStockBalanceReport() {
        reportsMainPage.clickOnCommonItemWihName("stockBalanceReport");
    }

    @Step
    public void openStockBalanceReportsPage(String storeName) {
        String storeId = ApiStorage.getCustomVariableStorage().getStores().get(storeName).getId();
        String posUrl = String.format("%s/reports/stockBalance?storeId=%s", UrlHelper.getWebFrontUrl(), storeId);
        reportsMainPage.getDriver().navigate().to(posUrl);
    }
}
