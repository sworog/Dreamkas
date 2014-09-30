package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.reports.ReportSteps;

public class ReportUserSteps {

    @Steps
    ReportSteps reportSteps;

    @When("пользователь нажимает на кнопку отчетов с названием 'Остатки товаров'")
    public void whenTheUSerClicksOnStockBalanceReportLink() {
        reportSteps.clickOnStockBalanceReport();
    }
}
