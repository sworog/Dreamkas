package project.lighthouse.autotests.jbehave.pos;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.pos.PosSteps;

public class PosUserSteps {

    @Steps
    PosSteps posSteps;

    @When("пользователь нажимает на кнопку Далее на странице выбора кассы")
    public void whenUserClicksOnFurtherButton() {
        posSteps.choosePosConfirmation();
    }
}
