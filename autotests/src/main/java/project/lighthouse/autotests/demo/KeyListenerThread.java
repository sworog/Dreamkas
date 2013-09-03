package project.lighthouse.autotests.demo;

public class KeyListenerThread extends Thread {

    ThucydidesControl thucydidesControl;

    public KeyListenerThread() {
        super("KeyListenerThread");
    }

    @Override
    public void run() {
        thucydidesControl = new ThucydidesControl();
    }

    public void setJlabelText(String text) {
        if (thucydidesControl != null) {
            thucydidesControl.setJLabelText(text);
            thucydidesControl.updateJLabelUI();
        }
    }
}
