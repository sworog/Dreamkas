package project.lighthouse.autotests.steps.departmentManager.order;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.departmentManager.order.OrderPage;

import static org.hamcrest.Matchers.equalTo;
import static org.junit.Assert.assertThat;

public class OrderSteps extends ScenarioSteps {

    OrderPage orderPage;

    @Step
    public void openPage() {
        orderPage.open();
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        orderPage.fieldInput(examplesTable);
    }

    @Step
    public void additionFormInput(ExamplesTable examplesTable) {
        orderPage.getProductAdditionForm().fieldInput(examplesTable);
    }

    @Step
    public void productCollectionExactCompare(ExamplesTable examplesTable) {
        orderPage.getOrderProductObjectCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void assertTotalSum(String totalSumValue) {
        assertThat(
                orderPage.getOrderTotalSumText(),
                equalTo(totalSumValue)
        );
    }

    @Step
    public void saveButtonClick() {
        orderPage.saveButtonClick();
    }

    @Step
    public void checksValues(ExamplesTable examplesTable) {
        orderPage.getProductAdditionForm().checkCardValue(examplesTable);
    }

    @Step
    public void assertFieldLabelTitle(String elementName) {
        orderPage.checkFieldLabel(elementName);
    }

    @Step
    public void assertAdditionProductFormLabelTitle(String elementName) {
        orderPage.getProductAdditionForm().checkFieldLabel(elementName);
    }
}
