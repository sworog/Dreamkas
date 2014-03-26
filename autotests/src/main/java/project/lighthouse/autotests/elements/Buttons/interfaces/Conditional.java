package project.lighthouse.autotests.elements.Buttons.interfaces;

/**
 * Interface to interact with expected conditions
 */
public interface Conditional {

    public Boolean isVisible();
    public Boolean isInvisible();

    public void shouldBeVisible();

    public void shouldBeNotVisible();
}
