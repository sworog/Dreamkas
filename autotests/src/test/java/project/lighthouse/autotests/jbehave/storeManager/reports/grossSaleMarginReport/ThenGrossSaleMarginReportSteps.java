package project.lighthouse.autotests.jbehave.storeManager.reports.grossSaleMarginReport;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.storeManager.reports.StoreGrossSaleMarginReportSteps;

public class ThenGrossSaleMarginReportSteps {

    @Steps
    StoreGrossSaleMarginReportSteps storeGrossSaleMarginReportSteps;

    @Then("the user checks the gross sale margin table contains expected value entries")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntries() {
        storeGrossSaleMarginReportSteps.compareWithExampleFixture();
    }

    @Then("the user checks the report name is '$reportName'")
    public void thenTheUserChecksTheReportName(String reportName) {
        storeGrossSaleMarginReportSteps.assertReportName(reportName);
    }

    @Then("the user checks there is no gross sale margin table with today date")
    public void thenTheUserChecksThereIsNoGrossSaleMarginTableWithTodayDate() {
        storeGrossSaleMarginReportSteps.notContainsValue();
    }

    @Then("the user checks the gross sale margin table contains expected five days ago entries")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsFiveDaysAgo() {
        storeGrossSaleMarginReportSteps.compareWithExampleForFiveDaysAgo();
    }

    @Then("the user checks the gross sale margin table with delayed purchase contains expected value entries")
    public void thenTheUserChecksTheGrossSaleMarginTableWithDelayedPurchaseContainsExpectedValueEntries() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForDelayedPurchases();
    }

    @Then("the user checks the gross sale margin table contains expected value entries for story 54.4")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesForStories544() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForStory544();
    }

    @Then("the user checks the gross sale margin table contains expected value entries after sale duplication with updated product is registered for story 54.4")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesAfterSaleDuplicationWithUpdatedProductRegisteredForStories544() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableSaleDuplicationWithUpdatedProductForStory544();
    }

    @Then("the user checks the gross sale margin table contains expected value entries after sale duplication with updated price is registered for story 54.4")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesAfterSaleDuplicationWithUpdatedPriceRegisteredForStories544() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableSaleDuplicationWithUpdatedPriceForStory544();
    }

    @Then("the user checks the gross sale margin table contains expected value entries after invoice product quantity is updated for story 54.2")
    @Alias("the user checks the gross sale margin table contains expected value entries after invoice product deletion for story 54.2")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesAfterInvoiceProductQuantityIsUpdatedForStories544() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForStory542IfInvoiceQuantityIsUpdated();
    }

    @Then("the user checks the gross sale margin table contains expected value entries after invoice product price is updated for story 54.2")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesAfterInvoiceProductPriceIsUpdatedForStories544() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForStory542IfInvoicePriceIsUpdated();
    }

    @Then("the user checks the gross sale margin table contains expected value entries after invoice date is updated for story 54.2")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesAfterDatePriceIsUpdatedForStories544() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForStory542IfInvoiceDateIsUpdated();
    }

    @Then("the user checks the gross sale margin table contains expected value entries if there were no invoices and resort happened")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesIfThereWereNoInvoicesAndResortHappened() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForStory545IfThereWereNoInvoices();
    }

    @Then("the user checks the gross sale margin table contains expected value entries if there were invoices and resort happened")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesIfThereWereInvoicesAndResortHappened() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForStory545IfThereWereInvoices();
    }

    @Then("the user checks the gross sale margin table contains expected value entries for story 56")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntriesForStory56() {
        storeGrossSaleMarginReportSteps.exactCompareWithExampleTableForStory56();
    }
}
