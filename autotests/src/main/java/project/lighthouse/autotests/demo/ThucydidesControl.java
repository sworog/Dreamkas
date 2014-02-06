package project.lighthouse.autotests.demo;

import project.lighthouse.autotests.StaticData;

import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.ArrayList;

public class ThucydidesControl extends JFrame {

    JPanel mainPanel;
    JPanel controlPanel;
    JScrollPane jScrollPane;
    JPanel stepsPanel;
    JLabel currentStep;
    JLabel currentScenarioName;

    ArrayList<JLabel> jLabelsSteps = new ArrayList<>();

    public ThucydidesControl() {
        initUI();
    }

    private void initUI() {

        mainPanel = new JPanel();
        mainPanel.setOpaque(true);
        mainPanel.setBackground(Color.WHITE);
        mainPanel.setLayout(new BoxLayout(mainPanel, BoxLayout.Y_AXIS));

        controlPanel = new JPanel();
        controlPanel.setOpaque(true);
        controlPanel.setBackground(Color.WHITE);
        controlPanel.setLayout(new BoxLayout(controlPanel, BoxLayout.PAGE_AXIS));
        controlPanel.setBorder(BorderFactory.createTitledBorder("Control Panel"));

        currentScenarioName = new JLabel();
        controlPanel.add(currentScenarioName, BorderLayout.PAGE_START);

        currentStep = new JLabel();
        controlPanel.add(currentStep, BorderLayout.CENTER);

        final JButton controlButton = new JButton("Start");

        controlButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent event) {
                switch (controlButton.getText()) {
                    case "Pause":
                        StaticData.isPaused = true;
                        controlButton.setText("Start");
                        break;
                    case "Start":
                        StaticData.isPaused = false;
                        controlButton.setText("Pause");
                        break;
                }
            }
        });

        controlPanel.add(controlButton, BOTTOM_ALIGNMENT);

        stepsPanel = new JPanel();
        jScrollPane = new JScrollPane(stepsPanel);
        stepsPanel.setOpaque(true);
        stepsPanel.setBackground(Color.WHITE);
        stepsPanel.setBorder(BorderFactory.createTitledBorder("Steps"));
        stepsPanel.setLayout(new BoxLayout(stepsPanel, BoxLayout.PAGE_AXIS));
        stepsPanel.setAutoscrolls(true);
        jScrollPane.setHorizontalScrollBarPolicy(JScrollPane.HORIZONTAL_SCROLLBAR_AS_NEEDED);
        jScrollPane.setVerticalScrollBarPolicy(JScrollPane.VERTICAL_SCROLLBAR_AS_NEEDED);

        mainPanel.add(controlPanel, BorderLayout.PAGE_START);
        mainPanel.add(jScrollPane, BorderLayout.PAGE_END);
        mainPanel.add(jScrollPane);
        setContentPane(mainPanel);
        pack();
        setTitle("Thucydides");
        setSize(300, 600);
        setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
        setVisible(true);
    }

    public void setCurrentStepText(String text) {
        currentStep.setText(text);
        currentStep.updateUI();
    }

    public void setScenarioName(String name) {
        currentScenarioName.setText(
                String.format("Scenario: %s", name));
        currentScenarioName.updateUI();
    }

    public void addStep(String text) {
        JLabel jLabelStep = new JLabel(text);

        Font currentStepFont = new Font(jLabelStep.getFont().getName(), Font.BOLD, jLabelStep.getFont().getSize());
        Font usualStepFont = new Font(jLabelStep.getFont().getName(), Font.PLAIN, jLabelStep.getFont().getSize());

        if (jLabelsSteps.isEmpty()) {
            jLabelStep.setFont(currentStepFont);
        } else {
            jLabelStep.setFont(currentStepFont);

            JLabel previousStep = jLabelsSteps.get(jLabelsSteps.size() - 1);
            previousStep.setFont(usualStepFont);
            previousStep.updateUI();
        }
        stepsPanel.add(jLabelStep);

        jLabelsSteps.add(jLabelStep);
        jLabelStep.updateUI();
    }

    public void removeScenarioStepJLabels() {
        for (JLabel jLabelStep : jLabelsSteps) {
            Container container = jLabelStep.getParent();
            container.remove(jLabelStep);
            container.validate();
            container.repaint();
        }
        jLabelsSteps.clear();
    }
}
