package ru.dreamkas.steps.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apihelper.UrlHelper;
import ru.dreamkas.elements.preLoader.BodyPreLoader;
import ru.dreamkas.pages.reports.ReportsMainPage;
import ru.dreamkas.pages.reports.grossMarginSales.GrossMarginSalesByGroupsReportPage;

public class ReportSteps extends ScenarioSteps {

    ReportsMainPage reportsMainPage;

    GrossMarginSalesByGroupsReportPage grossMarginSalesByGroupsReportPage;

    @Step
    public void clickOnStockBalanceReport() {
        reportsMainPage.clickOnCommonItemWihName("stockBalanceReport");
    }

    @Step
    public void clickOnGrossMarginSalesReport() {
        reportsMainPage.clickOnCommonItemWihName("grossMarginSalesReport");
    }

    @Step
    public void openStockBalanceReportsPage(String storeName) {
        String storeId = ApiStorage.getCustomVariableStorage().getStores().get(storeName).getId();
        String posUrl = String.format("%s/reports/stockBalance?storeId=%s", UrlHelper.getWebFrontUrl(), storeId);
        reportsMainPage.getDriver().navigate().to(posUrl);
    }

    @Step
    public void openGrossMarginSalesReportPage() {
        grossMarginSalesByGroupsReportPage.open();
    }

    @Step
    public void clickGroup(String groupName) {
        grossMarginSalesByGroupsReportPage.clickOnCollectionObjectByLocator(groupName);
        new BodyPreLoader(getDriver()).await();
    }
}
