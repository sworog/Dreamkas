package project.lighthouse.autotests.demo;

public class ThucydidesControlThread extends Thread {

    ThucydidesControl thucydidesControl;

    public ThucydidesControlThread() {
        super("ThucydidesControlThread");
    }

    @Override
    public void run() {
        thucydidesControl = new ThucydidesControl();
    }

    public void setCurrentStepText(String text) {
        if (thucydidesControl != null) {
            thucydidesControl.setCurrentStepText(text);
        }
    }

    public void addStep(String text) {
        thucydidesControl.addStep(text);
    }

    public void setScenarioName(String name) {
        //for the first method call thucydidesControl is null
        if (thucydidesControl != null) {
            thucydidesControl.setScenarioName(name);
        }
    }

    public void removeScenarioSteps() {
        if (thucydidesControl != null) {
            thucydidesControl.removeScenarioStepJLabels();
        }
    }
}
