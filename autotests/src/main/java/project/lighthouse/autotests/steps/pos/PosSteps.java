package project.lighthouse.autotests.steps.pos;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.pos.PosLaunchPage;

public class PosSteps extends ScenarioSteps {

    PosLaunchPage posLaunchPage;

    @Step
    public void choosePosConfirmation() {
        posLaunchPage.addObjectButtonClick();
    }
}
