package ru.dreamkas.steps.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apihelper.UrlHelper;
import ru.dreamkas.elements.preLoader.BodyPreLoader;
import ru.dreamkas.pages.reports.ReportsMainPage;
import ru.dreamkas.pages.reports.goodsGrossMarginSales.GoodsGrossMarginSalesByGroupsReportPage;
import ru.dreamkas.pages.reports.storesGrossMarginSales.StoresGrossMarginSalesReportPage;

public class ReportSteps extends ScenarioSteps {

    ReportsMainPage reportsMainPage;

    GoodsGrossMarginSalesByGroupsReportPage goodsGrossMarginSalesByGroupsReportPage;
    StoresGrossMarginSalesReportPage storesGrossMarginSalesReportPage;

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
    public void openGoodsGrossMarginSalesReportPage() {
        goodsGrossMarginSalesByGroupsReportPage.open();
    }

    @Step
    public void openStoresGrossMarginSalesReportPage() {
        storesGrossMarginSalesReportPage.open();
    }

    @Step
    public void clickGroup(String groupName) {
        goodsGrossMarginSalesByGroupsReportPage.clickOnCollectionObjectByLocator(groupName);
        new BodyPreLoader(getDriver()).await();
    }
}
