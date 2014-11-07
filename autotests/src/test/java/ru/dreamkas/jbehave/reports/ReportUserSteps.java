package ru.dreamkas.jbehave.reports;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.steps.CommonSteps;
import ru.dreamkas.steps.general.GeneralSteps;
import ru.dreamkas.steps.reports.ReportSteps;

public class ReportUserSteps {

    @Steps
    ReportSteps reportSteps;

    @Steps
    GeneralSteps generalSteps;

    @Steps
    CommonSteps commonSteps;

    @Given("пользователь открывает страницу отчета остатка товаров магазина с названием '$storeName'")
    public void givenTheUserOpensStockBalanceReportsPageByStoreName(String storeName) { reportSteps.openStockBalanceReportsPage(storeName); }

    @When("пользователь нажимает на кнопку отчетов с названием 'Остатки товаров'")
    public void whenTheUserClicksOnStockBalanceReportLink() {
        reportSteps.clickOnStockBalanceReport();
    }

    @Given("пользователь открывает страницу отчета по продажам и прибыли по товарам")
    public void whenTheUserOpensGoodsGrossMarginSalesReportPage() { reportSteps.openGoodsGrossMarginSalesReportPage(); }

    @Given("пользователь открывает страницу отчета по продажам и прибыли по сети")
    public void whenTheUserOpensStoresGrossMarginSalesReportPage() { reportSteps.openStoresGrossMarginSalesReportPage(); }

    @When("пользователь нажимает на кнопку отчетов с названием 'Продажи и прибыль по товарам'")
    public void whenTheUserClicksOnGrossMarginSalesReportLink() { reportSteps.clickOnGrossMarginSalesReport(); }

    @Given("пользователь кликает на группу '$groupName'")
    @When("пользователь кликает на группу '$groupName'")
    public void givenTheUserClicksGroup(String groupName) {
        reportSteps.clickGroup(groupName);
    }

    @Then("пользователь проверяет, что суммарные значения по продажам и прибыли по сети верны $examplesTable")
    public void thenTheUserChecksTotalStoresGrossMarginSalesValues(ExamplesTable examplesTable) {
        reportSteps.checksTotalStoresGrossMarginSalesValues(examplesTable);
    }

    @When("пользователь вводит в фильтр отчета даты с <dateFrom> по <dateTo>")
    @Alias("пользователь вводит в фильтр отчета даты с '$dateFrom' по '$dateTo'")
    public void whenUserFillsReportFilterDates(String dateFrom, String dateTo) {
        generalSteps.input("дата с", dateTo);
        commonSteps.waitForSimplePreloaderLoading();
        generalSteps.input("дата по", dateFrom);
        commonSteps.waitForSimplePreloaderLoading();
    }
}
