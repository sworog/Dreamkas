package project.lighthouse.autotests.jbehave.reports;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.reports.ReportSteps;

public class ReportUserSteps {

    @Steps
    ReportSteps reportSteps;

    @Given("пользователь открывает страницу отчета остатка товаров магазина с названием '$storeName'")
    public void givenTheUserOpensStockBalanceReportsPageByStoreName(String storeName) {
        reportSteps.openStockBalanceReportsPage(storeName);
    }

    @When("пользователь нажимает на кнопку отчетов с названием 'Остатки товаров'")
    public void whenTheUSerClicksOnStockBalanceReportLink() {
        reportSteps.clickOnStockBalanceReport();
    }
}
