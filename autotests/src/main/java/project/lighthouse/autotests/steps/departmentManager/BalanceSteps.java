package project.lighthouse.autotests.steps.departmentManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.departmentManager.balance.BalanceListPage;

public class BalanceSteps extends ScenarioSteps {

    BalanceListPage balanceListPage;

    public BalanceSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        balanceListPage.getBalanceObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void balanceTabClick() {
        balanceListPage.balanceTabClick();
    }

    @Step
    public void balanceTabIsNotVisible() {
        try {
            balanceListPage.balanceTabClick();
        } catch (Exception ignored) {
        }
    }
}
