package ru.dreamkas.jbehave.reports;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.elements.preLoader.BodyPreLoader;
import ru.dreamkas.steps.reports.ReportSteps;

public class ReportUserSteps {

    @Steps
    ReportSteps reportSteps;

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
}
