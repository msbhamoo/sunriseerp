<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.ai-settings-studio {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.ai-settings-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    margin-bottom: 24px;
}
.ai-settings-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
    color: #ffffff;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ai-key-box {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 18px;
    transition: all 0.2s ease;
}
.ai-key-box:hover, .ai-key-box:focus-within {
    border-color: #a78bfa;
    background: #ffffff;
    box-shadow: 0 8px 20px -4px rgba(139, 92, 246, 0.15);
}
.ai-key-box-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.ai-key-box-desc {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 12px;
}
.btn-save-ai {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 50%, #4f46e5 100%);
    color: #fff;
    border: none;
    font-weight: 700;
    padding: 12px 28px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.35);
    transition: all 0.25s ease;
    font-size: 14px;
}
.btn-save-ai:hover {
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 50%, #4338ca 100%);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(139, 92, 246, 0.45);
}
</style>

<div class="content-wrapper ai-settings-studio" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('system_settings'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>

            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="ai-settings-card">
                    <div class="ai-settings-header">
                        <div>
                            <h3 style="margin: 0; font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                                <i class="fa fa-magic" style="color: #a78bfa;"></i> AI Integration & API Keys Configuration
                            </h3>
                            <div style="font-size: 12px; color: #cbd5e1; margin-top: 4px;">
                                Manage permanent API credentials for AI Paper Generation and Handwritten Answer Sheet Vision Evaluation.
                            </div>
                        </div>
                        <span class="badge" style="background: rgba(255,255,255,0.15); font-size: 11px; padding: 6px 12px; border-radius: 20px; font-weight: 700; letter-spacing: 0.5px;">
                            GLOBAL ENGINE
                        </span>
                    </div>

                    <div class="box-body" style="padding: 24px;">
                        <div class="alert alert-info" style="border-radius: 10px; font-size: 13px; margin-bottom: 24px; border: 1px solid #bfdbfe; background: #eff6ff; color: #1e40af;">
                            <i class="fa fa-shield" style="font-size: 16px; margin-right: 6px;"></i>
                            <strong>Secure & Permanent Storage:</strong> These keys are securely persisted in the school database and automatically leveraged across all AI Exam Studio modules.
                        </div>

                        <form id="formAiSettings">
                            <!-- 1. Google Gemini -->
                            <div class="ai-key-box">
                                <div class="ai-key-box-title">
                                    <i class="fa fa-google" style="color: #4285f4; font-size: 16px;"></i> Google Gemini API Key
                                    <span class="badge bg-green" style="font-size: 10px; margin-left: 6px;">Recommended (Free Tier)</span>
                                </div>
                                <div class="ai-key-box-desc">
                                    Powers both the <strong>AI Question Paper Generator</strong> (Gemini 2.0 Flash) and <strong>Handwritten Physical Copy Multimodal Vision Evaluation</strong>.
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #ffffff; border-color: #cbd5e1;"><i class="fa fa-key text-muted"></i></span>
                                    <input type="password" class="form-control" id="ai_gemini_api_key" name="ai_gemini_api_key" value="<?php echo isset($result->ai_gemini_api_key) ? htmlspecialchars($result->ai_gemini_api_key) : ''; ?>" placeholder="AIzaSy..." style="border-color: #cbd5e1; font-family: monospace;">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" onclick="togglePass('ai_gemini_api_key', this)" style="border-color: #cbd5e1;"><i class="fa fa-eye"></i></button>
                                    </span>
                                </div>
                                <small style="display: block; margin-top: 6px; font-size: 11px; color: #64748b;">
                                    Get your free key from <a href="https://aistudio.google.com/app/apikey" target="_blank" style="color: #6366f1; font-weight: 600;">Google AI Studio <i class="fa fa-external-link"></i></a>
                                </small>
                            </div>

                            <!-- 2. Groq Cloud -->
                            <div class="ai-key-box">
                                <div class="ai-key-box-title">
                                    <i class="fa fa-bolt" style="color: #f59e0b; font-size: 16px;"></i> Groq Cloud API Key
                                    <span class="badge bg-purple" style="font-size: 10px; margin-left: 6px;">Ultra Fast</span>
                                </div>
                                <div class="ai-key-box-desc">
                                    Powers instantaneous CBSE blueprint question generation using <strong>LLaMA-3.3 70B Versatile</strong> at 500+ tokens/second.
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #ffffff; border-color: #cbd5e1;"><i class="fa fa-key text-muted"></i></span>
                                    <input type="password" class="form-control" id="ai_groq_api_key" name="ai_groq_api_key" value="<?php echo isset($result->ai_groq_api_key) ? htmlspecialchars($result->ai_groq_api_key) : ''; ?>" placeholder="gsk_..." style="border-color: #cbd5e1; font-family: monospace;">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" onclick="togglePass('ai_groq_api_key', this)" style="border-color: #cbd5e1;"><i class="fa fa-eye"></i></button>
                                    </span>
                                </div>
                                <small style="display: block; margin-top: 6px; font-size: 11px; color: #64748b;">
                                    Get your free key from <a href="https://console.groq.com/keys" target="_blank" style="color: #6366f1; font-weight: 600;">Groq Cloud Console <i class="fa fa-external-link"></i></a>
                                </small>
                            </div>

                            <!-- 3. OpenRouter API Key (Supports ox-alpha, DeepSeek, Claude, LLaMA) -->
                            <div class="ai-key-box">
                                <div class="ai-key-box-title">
                                    <i class="fa fa-cubes" style="color: #ec4899; font-size: 16px;"></i> OpenRouter API Key
                                    <span class="badge bg-green" style="font-size: 10px; margin-left: 6px;">New (ox-alpha Free Promo & 100+ Models)</span>
                                </div>
                                <div class="ai-key-box-desc">
                                    Enables frontier reasoning models including <strong>01-ai / ox-alpha (Fable 5 Tier)</strong>, DeepSeek-R1, and unified multi-provider access.
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #ffffff; border-color: #cbd5e1;"><i class="fa fa-key text-muted"></i></span>
                                    <input type="password" class="form-control" id="ai_openrouter_api_key" name="ai_openrouter_api_key" value="<?php echo isset($result->ai_openrouter_api_key) ? htmlspecialchars($result->ai_openrouter_api_key) : ''; ?>" placeholder="sk-or-v1-..." style="border-color: #cbd5e1; font-family: monospace;">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" onclick="togglePass('ai_openrouter_api_key', this)" style="border-color: #cbd5e1;"><i class="fa fa-eye"></i></button>
                                    </span>
                                </div>
                                <small style="display: block; margin-top: 6px; font-size: 11px; color: #64748b;">
                                    Get your API key from <a href="https://openrouter.ai/keys" target="_blank" style="color: #ec4899; font-weight: 600;">OpenRouter Console <i class="fa fa-external-link"></i></a>
                                </small>
                            </div>

                            <!-- 4. NVIDIA NIM API Key -->
                            <div class="ai-key-box">
                                <div class="ai-key-box-title">
                                    <i class="fa fa-microchip" style="color: #76b900; font-size: 16px;"></i> NVIDIA NIM API Key
                                    <span class="badge bg-green" style="font-size: 10px; margin-left: 6px; background-color: #76b900 !important;">Nemotron-3.5-Lightning-30B</span>
                                </div>
                                <div class="ai-key-box-desc">
                                    Powers high-precision reasoning via <strong>nvidia/nemotron-3.5-lightning-30b-a3b</strong> on the NVIDIA NIM cloud platform.
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #ffffff; border-color: #cbd5e1;"><i class="fa fa-key text-muted"></i></span>
                                    <input type="password" class="form-control" id="ai_nvidia_api_key" name="ai_nvidia_api_key" value="<?php echo isset($result->ai_nvidia_api_key) ? htmlspecialchars($result->ai_nvidia_api_key) : ''; ?>" placeholder="nvapi-..." style="border-color: #cbd5e1; font-family: monospace;">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" onclick="togglePass('ai_nvidia_api_key', this)" style="border-color: #cbd5e1;"><i class="fa fa-eye"></i></button>
                                    </span>
                                </div>
                                <small style="display: block; margin-top: 6px; font-size: 11px; color: #64748b;">
                                    Get your API key from <a href="https://build.nvidia.com" target="_blank" style="color: #76b900; font-weight: 600;">build.nvidia.com <i class="fa fa-external-link"></i></a>
                                </small>
                            </div>

                            <!-- 5. OpenAI API Key -->
                            <div class="ai-key-box">
                                <div class="ai-key-box-title">
                                    <i class="fa fa-codepen" style="color: #10b981; font-size: 16px;"></i> OpenAI API Key (Optional)
                                </div>
                                <div class="ai-key-box-desc">
                                    Optional fallback engine for GPT-4o Vision and text synthesis.
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #ffffff; border-color: #cbd5e1;"><i class="fa fa-key text-muted"></i></span>
                                    <input type="password" class="form-control" id="ai_openai_api_key" name="ai_openai_api_key" value="<?php echo isset($result->ai_openai_api_key) ? htmlspecialchars($result->ai_openai_api_key) : ''; ?>" placeholder="sk-proj-..." style="border-color: #cbd5e1; font-family: monospace;">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" onclick="togglePass('ai_openai_api_key', this)" style="border-color: #cbd5e1;"><i class="fa fa-eye"></i></button>
                                    </span>
                                </div>
                            </div>

                            <!-- 6. Default AI Model -->
                            <div class="ai-key-box">
                                <div class="ai-key-box-title">
                                    <i class="fa fa-sliders" style="color: #6366f1; font-size: 16px;"></i> Default Institution AI Engine
                                </div>
                                <div class="ai-key-box-desc">
                                    Select the default AI provider that loads automatically across exam creation workflows.
                                </div>
                                <select class="form-control" id="ai_default_model" name="ai_default_model" style="border-color: #cbd5e1; max-width: 450px;">
                                    <option value="nvidia" <?php echo (isset($result->ai_default_model) && $result->ai_default_model == 'nvidia') ? 'selected' : ''; ?>>🟢 NVIDIA NIM: Nemotron 3.5 Lightning 30B (Default)</option>
                                    <option value="openrouter_ox" <?php echo (isset($result->ai_default_model) && $result->ai_default_model == 'openrouter_ox') ? 'selected' : ''; ?>>🌟 OpenRouter: ox-alpha (Fable 5 Free Tier / Frontier Reasoning)</option>
                                    <option value="gemini" <?php echo (isset($result->ai_default_model) && $result->ai_default_model == 'gemini') ? 'selected' : ''; ?>>⚡ Google Gemini 2.0 Flash (Fast & Precise)</option>
                                    <option value="groq" <?php echo (isset($result->ai_default_model) && $result->ai_default_model == 'groq') ? 'selected' : ''; ?>>🚀 Groq Cloud: LLaMA-3.3 70B (High Speed)</option>
                                    <option value="openai" <?php echo (isset($result->ai_default_model) && $result->ai_default_model == 'openai') ? 'selected' : ''; ?>>OpenAI GPT-4o</option>
                                </select>
                            </div>

                            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                                <button type="button" id="btnSaveAiSettings" class="btn btn-save-ai" onclick="saveAiSettings()">
                                    <i class="fa fa-save"></i> Save AI Configuration
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
function togglePass(inputId, btn) {
    var input = document.getElementById(inputId);
    var icon = btn.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function saveAiSettings() {
    var btn = $('#btnSaveAiSettings');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

    $.ajax({
        url: '<?php echo site_url("admin/aisetting/save_ajax"); ?>',
        type: 'POST',
        data: $('#formAiSettings').serialize(),
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save AI Configuration');
            if (res.status === 'success') {
                successMsg(res.message);
            } else {
                errorMsg(res.message || 'Failed to save settings.');
            }
        },
        error: function(xhr, status, err) {
            btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save AI Configuration');
            errorMsg('Network error: ' + err);
        }
    });
}
</script>
